<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Ambil cart
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang masih kosong!');
        }

        // Ambil data produk dari session
        $products = session('products', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id_produk'] == $id) {

                    $itemSubtotal = $p['harga'] * $qty;
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'id_produk'  => $id,
                        'nama_produk' => $p['nama_produk'],
                        'foto'       => $p['foto'],
                        'harga'      => $p['harga'],
                        'qty'        => $qty,
                        'subtotal'   => $itemSubtotal
                    ];
                }
            }
        }

        // ================
        // DISKON BERDASARKAN SESSION 'voucher' 
        // ================
        $voucher = session('voucher');

        $discountPercent = $voucher['diskon'] ?? 0;
        $voucherCode     = $voucher['kode'] ?? '';
        $discountValue   = intval($subtotal * ($discountPercent / 100));

        $finalTotal = max(0, $subtotal - $discountValue);

        return view('checkout', [
            'items'          => $items,
            'subtotal'       => $subtotal,
            'discount'       => $discountPercent,
            'voucher_code'   => $voucherCode,
            'discount_value' => $discountValue,
            'total'          => $finalTotal,
        ]);
    }

    public function placeOrder(Request $request)
    {
        // validasi
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'post_code' => 'required|numeric',
            'phone' => 'required',
            'email' => 'required|email',
            'payment_method' => 'required',
        ]);

        // Ambil data cart dari session
        $cart = session('cart', []);
        $products = session('products', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        // Hitung subtotal
        $subtotal = 0;
        foreach ($cart as $id => $qty) {
            foreach ($products as $p) {
                if ($p['id_produk'] == $id) {
                    $subtotal += $p['harga'] * $qty;
                }
            }
        }

        // Hitung diskon
        $voucher = session('voucher');
        $discountPercent = $voucher['diskon'] ?? 0;
        $discountValue = intval($subtotal * ($discountPercent / 100));
        $finalTotal = max(0, $subtotal - $discountValue);

        // Simpan ke tabel tb_transaksi
        $transaksiId = DB::table('tb_transaksi')->insertGetId([
            'kode_transaksi'   => 'TRX-' . strtoupper(uniqid()),
            'id_user' => Auth::id() ?? 0,
            'id_admin'         => null,
            'id_voucher'       => $voucher['id'] ?? null,

            'total_harga'      => $finalTotal,
            'metode_pembayaran' => $request->payment_method,

            'status_order'     => 'menunggu',
            'status_pembayaran' => 'belum bayar',

            'address'          => $request->address,
            'city'             => $request->city,
            'country'          => $request->country,
            'post_code'        => $request->post_code,
            'order_note'       => $request->order_note ?? null,

            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Hapus cart dan voucher setelah order sukses
        session()->forget('cart');
        session()->forget('voucher');

        return redirect()->route('shop')
            ->with('success', 'Order berhasil dibuat! Kode transaksi: TRX-' . $transaksiId);
    }
}

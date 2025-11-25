<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;

class CartController extends Controller
{
    // Simulasi database produk via session
    private function bootProducts(): array
    {
        if (!session()->has('products')) {
            session([
                'products' => [
                    [
                        'id_produk' => 1,
                        'nama_produk' => 'Strip Legging',
                        'kategori' => 'Leggings',
                        'deskripsi' => 'Legging lembut dan nyaman untuk bayi.',
                        'harga' => 250000,
                        'foto' => 'assets/img/1.jpg'
                    ],
                    [
                        'id_produk' => 2,
                        'nama_produk' => 'Cute Pink Skirt',
                        'kategori' => 'Bottoms',
                        'deskripsi' => 'Rok pink imut untuk bayi perempuan.',
                        'harga' => 99000,
                        'foto' => 'assets/img/2.jpg'
                    ],
                    [
                        'id_produk' => 3,
                        'nama_produk' => 'Baby Denim Set',
                        'kategori' => 'Denim',
                        'deskripsi' => 'Setelan denim kasual untuk bayi.',
                        'harga' => 150000,
                        'foto' => 'assets/img/3.jpg'
                    ],
                    [
                        'id_produk' => 4,
                        'nama_produk' => 'Baby Hat',
                        'kategori' => 'Accessories',
                        'deskripsi' => 'Topi lembut melindungi kepala bayi.',
                        'harga' => 75000,
                        'foto' => 'assets/img/4.jpg'
                    ],
                ]
            ]);
        }

        return session('products');
    }

    private function findProduct($id)
    {
        $products = $this->bootProducts();
        foreach ($products as $p) {
            if ((int)$p['id_produk'] === (int)$id) {
                return $p;
            }
        }
        return null;
    }

    /** ================================
     * GET CART
     * ================================ */
    public function index(Request $request)
    {
        $this->bootProducts();

        $cart = session('cart', []);
        $items = [];
        $subtotal = 0;

        // Jika cart kosong → hapus session voucher
        if (empty($cart)) {
            session()->forget('voucher');
        }

        foreach ($cart as $productId => $qty) {
            $product = $this->findProduct($productId);

            if ($product) {
                $rowSubtotal = $product['harga'] * $qty;
                $subtotal += $rowSubtotal;

                $items[] = [
                    'id_produk' => $product['id_produk'],
                    'nama_produk' => $product['nama_produk'],
                    'foto' => $product['foto'],
                    'harga' => $product['harga'],
                    'quantity' => $qty,
                    'subtotal' => $rowSubtotal,
                ];
            }
        }

        /** ============================
         *  VOUCHER (Diskon)
         * ============================ */

        $voucher = session('voucher'); 
        $discountAmount = 0;

        if (!empty($voucher)) {
            $discountAmount = intval($subtotal * ($voucher['diskon'] / 100));
        }

        $final_total = max(0, $subtotal - $discountAmount);

        return view('cart', [
            'items'          => $items,
            'total'          => $subtotal,
            'discountAmount' => $discountAmount,
            'voucher'        => $voucher,
            'final_total'    => $final_total,
        ]);
    }

    /** ================================
     * APPLY VOUCHER
     * ================================ */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'required|string'
        ]);

        $kode = strtoupper(trim($request->kode_voucher));
        $today = Carbon::today()->toDateString();

        $voucher = Voucher::where('kode_voucher', $kode)
            ->where('status', 'aktif')
            ->where('tanggal_berlaku', '<=', $today)
            ->where('tanggal_expired', '>=', $today)
            ->first();

        if (!$voucher) {
            session()->forget('voucher');
            return back()->with('error', 'Voucher tidak valid atau sudah kedaluwarsa.');
        }

        if ($voucher->quantity <= 0) {
            return back()->with('error', 'Voucher sudah habis kuotanya.');
        }

        // simpan voucher ke session
        session([
            'voucher' => [
                'id'     => $voucher->id,
                'kode'   => $voucher->kode_voucher,
                'diskon' => $voucher->diskon,
            ]
        ]);

        return back()->with('success', 'Voucher berhasil diterapkan!');
    }

    /** ================================
     * ADD TO CART
     * ================================ */
    public function add(Request $request)
    {
        $request->validate(['id_produk' => 'required|integer']);

        $productId = (int)$request->id_produk;
        $product = $this->findProduct($productId);

        if (!$product) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $cart = session('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;

        session(['cart' => $cart]);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    /** ================================
     * REMOVE FROM CART
     * ================================ */
    public function remove(Request $request)
    {
        $request->validate(['id_produk' => 'required|integer']);

        $productId = (int)$request->id_produk;
        $cart = session('cart', []);

        unset($cart[$productId]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /** ================================
     * UPDATE CART (inc/dec)
     * ================================ */
    public function update(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|integer',
            'action'    => 'required|in:inc,dec'
        ]);

        $productId = (int)$request->id_produk;
        $cart = session('cart', []);

        if (!isset($cart[$productId])) {
            return back()->with('error', 'Produk tidak ada di keranjang.');
        }

        $cart[$productId] = $request->action === 'inc'
            ? $cart[$productId] + 1
            : max(1, $cart[$productId] - 1);

        session(['cart' => $cart]);

        return back()->with('success', 'Jumlah produk diperbarui.');
    }
}
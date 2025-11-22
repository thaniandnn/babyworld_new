<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // Simulasi "database" dengan session
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

    /** GET cart */
    public function index(Request $request)
    {
        $this->bootProducts();

        $cart = session('cart', []); // [id_produk => qty]
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = $this->findProduct($productId);
            if ($product) {
                $subtotal = $product['harga'] * $qty;
                $total += $subtotal;
                $items[] = [
                    'id_produk' => $product['id_produk'],
                    'nama_produk' => $product['nama_produk'],
                    'foto' => $product['foto'],
                    'harga' => $product['harga'],
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];
            }
        }

        $discount = (int) session('discount', 0);
        $voucher_code = session('voucher_code', '');
        $discount_amount = $total * ($discount / 100);
        $final_total = $total - $discount_amount;

        return view('cart', [
            'items' => $items,
            'total' => $total,
            'discount' => $discount,
            'voucher_code' => $voucher_code,
            'final_total' => $final_total,
            'voucher_msg' => session('voucher_msg'),
            'voucher_valid' => session('voucher_valid'),
        ]);
    }

    /** POST cart add */
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

    /** POST cart remove */
    public function remove(Request $request)
    {
        $request->validate(['id_produk' => 'required|integer']);

        $productId = (int)$request->id_produk;
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /** POST cart update (inc/dec) */
    public function update(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|integer',
            'action' => 'required|in:inc,dec'
        ]);

        $productId = (int)$request->id_produk;
        $cart = session('cart', []);

        if (!isset($cart[$productId])) {
            return back()->with('error', 'Produk tidak ada di keranjang.');
        }

        if ($request->action === 'inc') {
            $cart[$productId]++;
        } else {
            $cart[$productId] = max(1, $cart[$productId] - 1);
        }

        session(['cart' => $cart]);
        return back()->with('success', 'Jumlah produk diperbarui.');
    }
}

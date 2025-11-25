<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    // READ - List & Search
    public function index(Request $request)
    {
        $search = strtolower($request->get('search', ''));
        $kategori = $request->get('kategori', '');

        // Dummy data (this can later be replaced with DB data)
        $products = [
            [
                'id_produk' => 1,
                'nama_produk' => 'Cute Pink Skirt',
                'kategori' => 'Bottoms',
                'harga' => 99000,
                'foto' => '2.jpg',
                'deskripsi' => 'Rok bayi berwarna pink yang lucu dan lembut.',
            ],
            [
                'id_produk' => 2,
                'nama_produk' => 'Strip Legging',
                'kategori' => 'Leggings',
                'harga' => 250000,
                'foto' => '10.jpg',
                'deskripsi' => 'Legging bayi dengan motif garis dan bahan halus.',
            ],
            [
                'id_produk' => 3,
                'nama_produk' => 'Denim Baby Set',
                'kategori' => 'Denim',
                'harga' => 150000,
                'foto' => '3.jpg',
                'deskripsi' => 'Setelan denim bayi modis dan nyaman.',
            ],
            [
                'id_produk' => 4,
                'nama_produk' => 'Baby Hat',
                'kategori' => 'Accessories',
                'harga' => 50000,
                'foto' => 'hat.jpg',
                'deskripsi' => 'Topi lucu untuk bayi agar tetap hangat.',
            ],
        ];

        // Filter by kategori & search
        $filtered = array_filter($products, function ($p) use ($search, $kategori) { 
            $matchSearch = $search === '' ||
                str_contains(strtolower($p['nama_produk']), $search) ||
                str_contains(strtolower($p['kategori']), $search) ||
                str_contains(strtolower($p['deskripsi']), $search);

            $matchKategori = $kategori === '' || strcasecmp($p['kateg ori'], $kategori) === 0;

            return $matchSearch && $matchKategori;
        });

        return view('shop', [
            'products' => $filtered,
            'kategori' => $kategori,
            'search' => $search
        ]);
    }

    private function saveProducts($products)
    {
        session(['products' => $products]);
    }

    private function getProducts()
    {
        return session('products', []);
    }
}
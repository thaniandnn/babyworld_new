<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $username = session('logged_in_user');
        $current_address = session('address', 'Belum ada alamat.');
        
        $orders = [
            [ 
                'kode_transaksi' => 'BW123',
                'created_at' => now()->subDays(3),
                'status_order' => 'delivered',
                'total_harga' => 250000,
            ],
            [
                'kode_transaksi' => 'BW456',
                'created_at' => now()->subDays(7),
                'status_order' => 'pending',
                'total_harga' => 175000,
            ],
        ];

        return view('accounts', compact('username', 'current_address', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        session(['logged_in_user' => $request->username]);
        return redirect()->route('accounts')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateAddress(Request $request)
    {
        session(['address' => $request->address]);
        return redirect()->route('accounts')->with('success', 'Alamat berhasil disimpan!');
    }

    public function changePassword(Request $request)
    {
        // Simulasi logika ganti password
        if ($request->new_password !== $request->confirm_password) {
            return redirect()->route('accounts')->with('error', 'Konfirmasi password tidak cocok!');
        }

        return redirect()->route('accounts')->with('success', 'Password berhasil diubah!');
    }
}

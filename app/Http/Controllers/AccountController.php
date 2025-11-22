<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        // Ambil user login
        $user = User::where('name', session('logged_in_user'))->first();

        if (!$user) {
            return redirect()->route('login-register.page')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika nanti sudah ada model transaksi → pakai relasi:
        // $orders = $user->transactions;

        // TEMPORARY dummy orders
        $orders = [
            (object)[
                'kode_transaksi' => 'BW123',
                'created_at' => now()->subDays(3),
                'status' => 'delivered',
                'total_harga' => 250000,
            ],
            (object)[
                'kode_transaksi' => 'BW456',
                'created_at' => now()->subDays(7),
                'status' => 'pending',
                'total_harga' => 175000,
            ],
        ];

        return view('accounts', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user = User::where('name', session('logged_in_user'))->first();

        // Cek email tidak dipakai user lain
        if (User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
            return redirect()->route('accounts')->with('error', 'Email sudah digunakan user lain!');
        }

        // Update data
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        // Refresh session name
        session(['logged_in_user' => $user->name]);

        return redirect()->route('accounts')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);

        $user = User::where('name', session('logged_in_user'))->first();
        $user->address = $request->address;
        $user->save();

        return redirect()->route('accounts')->with('success', 'Alamat berhasil disimpan!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:5',
            'confirm_password' => 'required|min:5|same:new_password',
        ]);

        $user = User::where('name', session('logged_in_user'))->first();

        // Cek current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('accounts')->with('error', 'Password saat ini salah!');
        }

        // Update password (auto hashed by model)
        $user->password = $request->new_password;
        $user->save();

        return redirect()->route('accounts')->with('success', 'Password berhasil diubah!');
    }

    public function deleteAccount(Request $request)
    {
        $user = User::where('name', session('logged_in_user'))->first();

        if (!$user) {
            return redirect()->route('accounts')->with('error', 'User tidak ditemukan.');
        }

        $user->delete(); // <-- ini yang hapus dari database

        session()->forget('logged_in_user'); // hapus session
        session()->flush();

        return redirect()->route('login-register.page')->with('success', 'Akun berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * TAMPILKAN HALAMAN LOGIN & REGISTER
     */
    public function loginRegister()
    {
        return view('login-register');
    }

    /**
     * PROSES LOGIN (PAKAI DATABASE)
     */
    public function loginProcess(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Ambil user dari database berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan atau password salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah!');
        }

        // Simpan session user
        session(['logged_in_user' => $user->name]);

        return redirect()->route('shop')->with('success', 'Login berhasil!');
    }

    /**
     * PROSES REGISTER (PAKAI DATABASE)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:5',
            'security_question' => 'required',
            'security_answer'   => 'required'
        ]);

        // Simpan user baru ke database
        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => $request->password,
            'security_question' => $request->security_question,
            'security_answer'   => $request->security_answer,
            'role'              => 'customer'
        ]);

        return redirect()->route('login-register.page')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /**
     * HALAMAN LUPA PASSWORD
     */
    public function forgotPassword()
    {
        return view('forgot-password');
    }

    /**
     * RESET PASSWORD (PAKAI DATABASE)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'            => 'required|email',
            'security_answer'  => 'required',
            'new_password'     => 'required|min:5'
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        // Cocokkan jawaban keamanan
        if ($user->security_answer !== $request->security_answer) {
            return back()->with('error', 'Jawaban keamanan salah.');
        }

        // Update password
        $user->password = $request->new_password;
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->session()->forget('logged_in_user');
        return redirect()->route('login-register.page')
            ->with('success', 'Kamu sudah logout.');
    }
}

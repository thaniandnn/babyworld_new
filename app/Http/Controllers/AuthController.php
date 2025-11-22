<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $defaultUsers = [
        [
            'name' => 'Nadine Nathania',
            'email' => 'thaniandnn@gmail.com',
            'password' => '12345',
            'security_question' => 'Apa makanan favoritmu?',
            'security_answer' => 'bakso'
        ]
    ];

    public function loginRegister(Request $request)
    {
        // jika belum ada session user list, set default
        if (!$request->session()->has('users')) {
            $request->session()->put('users', $this->defaultUsers);
        }
        return view('login-register');
    }

    public function index()
    {
        return view('index');
    }

    public function loginProcess(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $users = session('users', []);

        foreach ($users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                session(['logged_in_user' => $user['name']]);
                return redirect()->route('shop')->with('success', 'Login berhasil!');
            }
        }
        return back()->with('error', 'Email atau password salah!');
    }

    public function register(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $security_question = $request->input('security_question');
        $security_answer = $request->input('security_answer');
        $users = session('users', []);
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return back()->with('error', 'Email sudah terdaftar!');
            }
        }
        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'security_question' => $security_question,
            'security_answer' => $security_answer
        ];

        $users[] = $newUser;

        session(['users' => $users]);
        return redirect()->route('login.register')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function resetPassword(Request $request)
    {
        $newPassword = $request->input('new_password');
        $email = $request->input('email');
        $answer = $request->input('security_answer');
        $users = session('users', []);
        $found = false;

        foreach ($users as &$user) {
            if ($user['email'] == $email && $user['security_answer'] === $answer) {
                $user['password'] = $newPassword;
                $found = true; 
                break;
            }
        }

        if ($found) {
            session(['users' => $users]);
            return back()->with('success', 'Password berhasil diubah');
        } else {
            return back()->with('error', 'Data tidak mcocok');
        }
    }


    public function logout(Request $request)
    {
        $request->session()->forget(['logged_in_user']);
        return redirect()->route('login.register')->with('success', 'Kamu sudah logout.');
    }
}

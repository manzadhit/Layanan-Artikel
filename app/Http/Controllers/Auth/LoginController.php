<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi data yang diterima dari request
        $this->validator($request->all())->validate();

        // Coba untuk login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            // Cek peran pengguna setelah berhasil login
            if (Auth::user()->role === 'admin') {
                // Redirect ke dashboard admin jika pengguna adalah admin
                return redirect()->route('dashboard', ["type" => "posts"]);
            } else {
                // Redirect ke halaman utama (home) jika pengguna adalah user biasa
                return redirect()->route('home');
            }
        }

        // Jika gagal login, kembali ke halaman login dengan error
        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showDeleteForm()
    {
        return view('account.delete');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors([
                'password' => 'The provided password does not match your current password.',
            ]);
        }

        // Pastikan logout berhasil sebelum menghapus akun
        Auth::logout();

        if ($user->delete()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'Your account has been deleted.');
        } else {
            return redirect()->back()->with('error', 'There was a problem deleting your account.');
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewUserNotification;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi data yang diterima dari request
        $this->validator($request->all())->validate();

        // Membuat user baru dan menyimpan ke database
        $user = $this->create($request->all());

        // Redirect ke halaman home setelah berhasil registrasi
        return redirect('/login');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        // Ambil kata pertama dari nama
        $firstName = Str::slug(strtok($data['name'], " "));

        // Tentukan username awal dari kata pertama nama
        $username = $firstName;

        // Cek apakah username sudah ada, jika ada tambahkan angka unik di belakangnya
        $count = 1;
        while (User::where('username', $username)->exists()) {
            $username = $firstName . '-' . $count++;
        }

        // Buat user baru dan simpan ke database
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'username' => $username,
            'profile_color' => $this->getRandomColor(),
        ]);

        // Simpan user ke database
        $user->save();

        // Notify admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewUserNotification($user));
        }

        return $user;
    }

    private function getRandomColor(): string
    {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[rand(0, 15)];
        }
        return $color;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\DatabaseNotification;

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

        // Hapus notifikasi yang terkait dengan user secara langsung
        DatabaseNotification::where('data->reportable_id', (string) $user->id)
            ->where('data->reportable_type', 'App\\Models\\User')
            ->delete();

        // Hapus semua notifikasi yang memiliki user_id terkait
        DatabaseNotification::where('data->user_id', (string) $user->id)
            ->delete();

        // Hapus laporan yang terkait dengan user secara langsung
        Report::where('reportable_id', $user->id)
            ->where('reportable_type', 'App\\Models\\User')
            ->delete();

        // Hapus notifikasi dan laporan yang terkait dengan post yang dimiliki oleh user
        foreach ($user->posts as $post) {
            DatabaseNotification::where('data->reportable_id', (string) $post->id)
                ->where('data->reportable_type', 'App\\Models\\Post')
                ->delete();

            Report::where('reportable_id', $post->id)
                ->where('reportable_type', 'App\\Models\\Post')
                ->delete();

            // Hapus notifikasi dan laporan terkait dengan komentar di post ini
            foreach ($post->comments as $comment) {
                DatabaseNotification::where('data->reportable_id', (string) $comment->id)
                    ->where('data->reportable_type', 'App\\Models\\Comment')
                    ->delete();

                Report::where('reportable_id', $comment->id)
                    ->where('reportable_type', 'App\\Models\\Comment')
                    ->delete();
            }
        }

        // Hapus notifikasi dan laporan terkait dengan komentar yang dibuat oleh user
        $comments = $user->comments;
        foreach ($comments as $comment) {
            DatabaseNotification::where('data->reportable_id', (string) $comment->id)
                ->where('data->reportable_type', 'App\\Models\\Comment')
                ->delete();

            Report::where('reportable_id', $comment->id)
                ->where('reportable_type', 'App\\Models\\Comment')
                ->delete();
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

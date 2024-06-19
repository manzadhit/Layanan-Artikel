<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index($username, $menu = null)
    {
        $user_login = Auth::user();
        $user = User::where('username', $username)->first();

        if (!$user) {
            abort(404, 'User not found');
        }

        $followedUsers = $user->following()->latest()->limit(5)->get();

        // Mengambil postingan atau notifikasi berdasarkan menu yang dipilih
        switch ($menu) {
            case 'notifications':
                if ($user->id !== $user_login->id) {
                    abort(403, 'Unauthorized action.');
                }
                $notifications = $user->notifications()->latest()->get();
                $userResponded = $notifications->map(function ($notification) {
                    if ($notification->type === 'App\Notifications\UserFollowed') {
                        // Notifikasi pengguna yang diikuti
                        return User::find($notification->data["follower_id"] ?? null);
                    } elseif ($notification->type === 'App\Notifications\PostLiked') {
                        // Notifikasi postingan yang disukai
                        return User::find($notification->data["liker_id"] ?? null);
                    } elseif ($notification->type === 'App\Notifications\PostSaved') {
                        // Notifikasi postingan yang disimpan
                        return User::find($notification->data["saver_id"] ?? null);
                    } elseif ($notification->type === 'App\Notifications\PostCommented') {
                        // Notifikasi postingan yang dikomentari
                        return User::find($notification->data["commenter_id"] ?? null);
                    } elseif ($notification->type === 'App\Notifications\NewUserNotification') {
                        // Notifikasi postingan yang dikomentari
                        return User::find($notification->data["user_id"] ?? null);
                    }else {
                        // Handle other types of notifications here
                        return null;
                    }
                })->filter();

                $user->unreadNotifications->markAsRead();

                return view('profile.index', compact('user', 'notifications', 'userResponded', 'menu', 'user_login', 'followedUsers'));
            case 'saved':
                $posts = $user->savedPosts()->latest('saved_posts.created_at')->get();
                break;
            case 'liked':
                $posts = $user->likes()->with('post')->latest()->get()->pluck('post');
                break;
            case "created":
                $posts = $user->posts()->latest()->get();
                break;
            default:
                $posts = $user->posts()->latest()->get();
                break;
        }

        // Mengambil daftar pengguna yang diikuti

        $posts->each(function ($post) {
            $post->isSaved = $post->savedByUsers()->where('user_id', auth()->id())->exists();
        });

        // Mendefinisikan fungsi getFirstTagRegex sebagai variabel
        $getFirstTagRegex = function ($content, $count = 3) {
            // Pola regex untuk menangkap tag <p> pertama hingga ketiga
            preg_match_all('/<p[^>]*>(.*?)<\/p>/s', $content, $matches);

            // Periksa apakah ada yang cocok dengan pola regex
            if (!empty($matches[0])) {
                // Ambil maksimal $count paragraf pertama
                $texts = array_slice($matches[0], 0, $count);

                // Hapus tag HTML dari setiap paragraf
                $cleanedTexts = array_map(function ($text) {
                    return strip_tags($text);
                }, $texts);

                // Gabungkan hasilnya menjadi satu teks
                $combinedText = implode(' ', $cleanedTexts);

                return $combinedText;
            } else {
                // Jika tidak ada tag <p> yang ditemukan, kembalikan pesan atau nilai default
                return '';
            }
        };


        return view('profile.index', compact('user_login', 'user', 'posts', 'menu', 'getFirstTagRegex', 'followedUsers'));
    }
    public function edit($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return redirect()->route('profile.edit')->with('error', 'Pengguna tidak ditemukan');
        }

        return view('profile.editProfile', compact('user')); // Mengirim data pengguna ke view
    }

    // Metode untuk menyimpan perubahan profil
    public function update(Request $request, $username)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = User::where('username', $username)->first();

        if (!$user) {
            return redirect()->route('profile.edit', $username)->with('error', 'Pengguna tidak ditemukan');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('profile', $username)->with('success', 'Profil berhasil diperbarui');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Hapus gambar lama jika ada
            if ($user->profile_image && file_exists(public_path('profile_images/' . $user->profile_image))) {
                unlink(public_path('profile_images/' . $user->profile_image));
            }

            // Pindahkan gambar baru ke folder public
            $image->move(public_path('profile_images'), $imageName);

            // Simpan nama file ke database
            $user->profile_image = $imageName;
            $user->save();

            return redirect()->route('profile.edit', $user->username)
                ->with('success', 'Gambar profil berhasil diunggah');
        }

        return redirect()->route('profile.edit', $user->username)
            ->with('error', 'Gagal mengunggah gambar profil');
    }

    public function removeImage()
    {
        $user = Auth::user();

        if ($user->profile_image) {
            $imagePath = public_path('profile_images/' . $user->profile_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $user->profile_image = null;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}

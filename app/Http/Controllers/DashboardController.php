<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Report;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends Controller
{
    public function index(Request $request, $type)
    {

        $query  = $request->input('q');
        $results = [];
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

        if (isset($query)) {

            switch ($type) {
                case "posts":
                    $results['posts'] = Post::query()
                        ->where('title', 'like', '%' . $query  . '%')
                        ->orWhere('content', 'like', '%' . $query  . '%')
                        ->latest()->get();
                    break;
                case "users":
                    $results['users'] = User::query()
                        ->where('name', 'like', '%' . $query  . '%')
                        ->orWhere('email', 'like', '%' . $query  . '%')
                        ->latest()->get();
                    break;
                case "categories":
                    $results['categories'] = Category::query()
                        ->where('name', 'like', '%' . $query  . '%')
                        ->latest()->get();
                    break;
                default:
                    $results['posts'] = Post::query()
                        ->where('title', 'like', '%' . $query  . '%')
                        ->orWhere('content', 'like', '%' . $query  . '%')
                        ->latest()->get();
                    break;
            }
        } else {

            switch ($type) {
                case "posts":
                    $results['posts'] = Post::query()->latest()->get();
                    break;
                case "users":
                    $results['users'] = User::query()->latest()->get();
                    break;
                case "categories":
                    $results['categories'] = Category::query()->latest()->get();
                    break;
                default:
                    $results['posts'] = Post::query()->latest()->get();
                    break;
            }
        }

        return view('admin.dashboard', compact('results', 'type', 'getFirstTagRegex'));
    }

    public function deleteUser($type, User $user)
    {
        try {
            // Hapus notifikasi yang terkait dengan user secara langsung
            DatabaseNotification::where('data->reportable_id', (string) $user->id)
                ->where('data->reportable_type', 'App\\Models\\User')
                ->delete();

            // Hapus semua notifikasi yang memiliki user_id terkait
            DatabaseNotification::where('data->user_id', $user->id)
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

            // Hapus notifikasi dan laporan yang dibuat oleh user
            Report::where('user_id', $user->id)->delete();

            // Hapus user
            $user->delete();

            return redirect()->route('dashboard', ['type' => 'users'])->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }



    public function deleteCategory($type, Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('dashboard', ['type' => $type])->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard', ['type' => $type])->with('error', 'Failed to delete Category.');
        }
    }
}

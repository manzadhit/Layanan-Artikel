<?php

namespace App\Http\Controllers;

use App\Models\PostImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('upload');
        $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/images', $imageName);

        // Kembalikan URL gambar yang diunggah untuk penggunaan di editor
        return response()->json(['url' => asset('storage/images/' . $imageName)]);
    }
}

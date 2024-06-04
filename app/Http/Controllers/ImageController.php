<?php

// app/Http/Controllers/ImageController.php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // sesuaikan dengan kebutuhan validasi Anda
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');

            $image = new Image();
            $image->post_id = $request->post_id;
            $image->image_path = $imagePath;
            $image->save();

            return response()->json(['message' => 'Image uploaded successfully', 'image' => $image], 201);
        }

        return response()->json(['message' => 'Image upload failed'], 400);
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('media'), $fileName);

            $url = asset('media/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
        }
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}


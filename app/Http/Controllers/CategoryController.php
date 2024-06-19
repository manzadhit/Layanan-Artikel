<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view("categories.categories", compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create'); // Tampilkan view untuk form pembuatan kategori
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|unique:categories|max:255',
            // Sesuaikan validasi sesuai kebutuhan
        ]);

        // Buat kategori baru berdasarkan data yang divalidasi
        $category = Category::create([
            'name' => $validatedData['name'],
            // Tambahkan field lain sesuai kebutuhan
            'slug' => Str::slug($request->name),
        ]);

        // Redirect ke halaman atau rute yang sesuai setelah kategori berhasil dibuat
        return redirect()->route('dashboard', ["type" => "categories"])->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $getFirstTagRegex = function ($content) {
            preg_match('/<p>(.*?)<\/p>/s', $content, $matches);
            // Periksa apakah $matches memiliki kunci indeks 1
            if (isset($matches[1])) {
                // Menghapus tag <figure> jika ada di dalam teks
                $text = strip_tags($matches[1]);
                return $text;
            } else {
                // Jika tidak ada tag <p> yang ditemukan, kembalikan pesan atau nilai default
                return '';
            }
        };
        $category = Category::query()->where('slug', $slug)->first();
        return view("categories.index", [
            "category" => $category,
            "posts" => $category->posts()->latest()->get(),
            'getFirstTagRegex' => $getFirstTagRegex
        ]);
    }

    public function follow(Category $category)
    {
        auth()->user()->followedCategories()->toggle($category->id);

        return back()->with('success', 'Category follow status updated.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('dashboard', ['type' => 'categories'])->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua jenjang
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Form tambah jenjang
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan jenjang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_category' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Jenjang berhasil ditambahkan!');
    }

    /**
     * Form edit jenjang
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update jenjang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_category' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Jenjang berhasil diupdate!');
    }

    /**
     * Hapus jenjang
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Cek apakah jenjang digunakan di course
        if ($category->courses()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Jenjang tidak dapat dihapus karena masih digunakan di kursus!');
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Jenjang berhasil dihapus!');
    }
}
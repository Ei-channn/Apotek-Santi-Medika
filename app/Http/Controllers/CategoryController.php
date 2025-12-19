<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function search(Request $request)
    {
        return Category::withCount('medicines')
            ->where('name', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }


    // Tampilkan semua kategori
    public function index()
    {
        $categories = Category::withCount('medicines')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    // Form tambah kategori
    public function create()
    {
        return view('categories.create');
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    // Form edit kategori
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Update kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    // Hapus kategori
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}

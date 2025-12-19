<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Category;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    // Tampilkan semua obat
    public function index()
    {
        $medicines = Medicine::with('category')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('medicines.index', compact('medicines'));
    }

    // Form tambah obat
    public function create()
    {
        $categories = Category::all();
        return view('medicines.create', compact('categories'));
    }

    public function search(Request $request)
    {
        return Medicine::with('category')
        ->where('code', 'like', '%' . $request->q . '%')
        ->orWhere('name', 'like', '%' . $request->q . '%')
        ->orWhereHas('category', function ($cat) use ($request) {
            $cat->where('name', 'like', '%' . $request->q . '%');
        })
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();
    }

    // Simpan obat baru
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:medicines',
            'name' => 'required',
            'category_id' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expired_date' => 'required|date'
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')
            ->with('success', 'Obat berhasil ditambahkan');
    }

    // Form edit obat
    public function edit(Medicine $medicine)
    {
        $categories = Category::all();
        return view('medicines.edit', compact('medicine', 'categories'));
    }

    // Update data obat
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'code' => 'required|unique:medicines,code,' . $medicine->id,
            'name' => 'required',
            'category_id' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expired_date' => 'required|date'
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')
            ->with('success', 'Obat berhasil diperbarui');
    }

    // Hapus obat
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'Obat berhasil dihapus');
    }
}

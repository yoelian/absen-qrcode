<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['employees', 'positions'])->paginate(20);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'code' => 'required|string|unique:categories,code|max:10',
            'badge_color' => 'required|string|max:50',
            'target_in_time' => 'nullable|date_format:H:i',
            'target_out_time' => 'nullable|date_format:H:i',
        ]);

        // Paksa kode menjadi huruf besar
        $code = strtoupper($request->code);

        Category::create([
            'name' => $request->name,
            'code' => $code,
            'badge_color' => $request->badge_color,
            'target_in_time' => $request->target_in_time,
            'target_out_time' => $request->target_out_time,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $category->loadCount('employees');
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'code' => 'required|string|max:10|unique:categories,code,' . $category->id,
            'badge_color' => 'required|string|max:50',
            'target_in_time' => 'nullable|date_format:H:i',
            'target_out_time' => 'nullable|date_format:H:i',
        ]);

        $category->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'badge_color' => $request->badge_color,
            'target_in_time' => $request->target_in_time,
            'target_out_time' => $request->target_out_time,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Cek jika kategori masih memiliki relasi employee
        if ($category->employees()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki relasi dengan data Siswa / Staff!');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

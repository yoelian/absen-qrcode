<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Category;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('positions')->get();

        $query = Position::with('category')->withCount('employees');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $positions = $query->orderBy('category_id')->orderBy('name')->paginate(25)->withQueryString();

        return view('positions.index', compact('positions', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('positions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
        ]);

        // Cek unik per kategori
        $exists = Position::where('category_id', $request->category_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Jabatan/Kelas ini sudah ada di kategori tersebut.'], 422);
            }
            return back()->withErrors(['name' => 'Jabatan/Kelas ini sudah ada di kategori tersebut.'])->withInput();
        }

        $position = Position::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Jabatan/Kelas berhasil ditambahkan.',
                'position' => $position
            ]);
        }

        return redirect()->route('positions.index')->with('success', 'Jabatan/Kelas berhasil ditambahkan.');
    }

    public function edit(Position $position)
    {
        $position->loadCount('employees');
        $categories = Category::all();
        return view('positions.edit', compact('position', 'categories'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
        ]);

        // Cek unik per kategori, kecuali diri sendiri
        $exists = Position::where('category_id', $request->category_id)
            ->where('name', $request->name)
            ->where('id', '!=', $position->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Jabatan/Kelas ini sudah ada di kategori tersebut.'])->withInput();
        }

        $position->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
        ]);

        return redirect()->route('positions.index')->with('success', 'Jabatan/Kelas berhasil diperbarui.');
    }

    public function destroy(Position $position)
    {
        if ($position->employees()->count() > 0) {
            return redirect()->route('positions.index')
                ->with('error', 'Jabatan/Kelas tidak dapat dihapus karena masih digunakan oleh ' . $position->employees()->count() . ' anggota!');
        }

        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Jabatan/Kelas berhasil dihapus.');
    }

    /**
     * API: Ambil daftar posisi berdasarkan category_id (untuk dropdown dinamis)
     */
    public function byCategory(Category $category)
    {
        $positions = Position::where('category_id', $category->id)->orderBy('name')->get(['id', 'name']);
        return response()->json($positions);
    }
}

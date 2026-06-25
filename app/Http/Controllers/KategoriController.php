<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('created_at', 'desc')->get();
        
        // Menghitung statistik untuk widget
        $totalKategori = Kategori::count();
        
        return view('kategori', compact('kategoris', 'totalKategori'));
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = Kategori::create([
            'prefix_sku' => strtoupper($request->prefix_sku),
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori baru berhasil ditambahkan!',
                'data' => $kategori
            ]);
        }

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function update(UpdateKategoriRequest $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->update([
            'prefix_sku' => strtoupper($request->prefix_sku),
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui!',
                'data' => $kategori
            ]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            
            // Cek jika dipakai di Product
            $isUsed = \App\Models\Product::where('kategori_id', $kategori->id)->exists();
            if ($isUsed) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kategori tidak dapat dihapus karena ada material yang menggunakannya.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena ada material yang menggunakannya.');
            }

            $kategori->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil dihapus!'
                ]);
            }

            return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus kategori.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSatuanRequest;
use App\Http\Requests\UpdateSatuanRequest;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function store(StoreSatuanRequest $request)
    {
        $satuan = Satuan::create([
            'nama_satuan' => $request->nama_satuan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil ditambahkan',
            'data' => $satuan
        ]);
    }

    public function update(UpdateSatuanRequest $request, $id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->update([
            'nama_satuan' => $request->nama_satuan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Satuan berhasil diperbarui',
            'data' => $satuan
        ]);
    }

    public function destroy($id)
    {
        try {
            $satuan = Satuan::findOrFail($id);
            // Cek apakah dipakai di product (opsional, tapi disarankan)
            $isUsed = \App\Models\Product::where('satuan', $satuan->nama_satuan)->exists();
            if ($isUsed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus: Satuan ini sedang digunakan oleh material.'
                ], 400);
            }

            $satuan->delete();
            return response()->json([
                'success' => true,
                'message' => 'Satuan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus satuan'
            ], 500);
        }
    }
}

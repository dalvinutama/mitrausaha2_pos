<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Services\InventoryService;

class PersediaanController extends Controller
{
    public function index()
    {
        // Ambil semua produk beserta nama kategorinya
        $products = Product::with('kategori')->orderBy('created_at', 'desc')->get();
        
        // Ambil semua kategori untuk dimasukkan ke dropdown form tambah barang
        $kategoris = Kategori::all();
        // Ambil semua satuan
        $satuans = Satuan::all();
        
        // Hitung total untuk widget
        $totalProduk = Product::count();
        $totalStok = Product::sum('stok');

        return view('persediaan', compact('products', 'kategoris', 'satuans', 'totalProduk', 'totalStok'));
    }

    public function store(StoreProductRequest $request)
    {

        // LOGIKA PEMBUATAN SKU OTOMATIS
        $kategori = Kategori::findOrFail($request->kategori_id);
        $prefix = $kategori->prefix_sku;

        $lastProduct = Product::where('kategori_id', $kategori->id)->orderBy('id', 'desc')->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $skuGenerate = $prefix . '-' . $newNumber;

        // HITUNG REORDER POINT (ROP) & EOQ
        // Karena ini barang baru, historinya belum ada (Permintaan = 0)
        // Kita simpan produknya dulu, baru kita panggil service
        $product = Product::create([
            'kategori_id'       => $request->kategori_id,
            'sku'               => $skuGenerate,
            'barcode'           => $request->barcode,
            'nama_barang'       => $request->nama_barang,
            'stok'              => 0, 
            'harga_beli'        => $request->harga_beli,
            'harga_jual'        => $request->harga_jual,
            'satuan'            => $request->satuan,
            'lead_time_hari'    => $request->lead_time_hari,
            'tipe_safety_stock' => $request->tipe_safety_stock,
            'safety_stock'      => $request->safety_stock ?? 0,
            'reorder_point'     => 0,
            'eoq'               => 0
        ]);

        // Kalkulasi ROP & EOQ Dinamis
        $inventoryService = new InventoryService();
        $inventoryService->recalculateForProduct($product);

        return redirect()->back()->with('success', 'Barang baru berhasil ditambahkan dengan SKU: ' . $skuGenerate);
    }

    // FUNGSI UPDATE UNTUK EDIT BARANG
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'kategori_id'       => $request->kategori_id,
            'barcode'           => $request->barcode,
            'nama_barang'       => $request->nama_barang,
            'harga_beli'        => $request->harga_beli,
            'harga_jual'        => $request->harga_jual,
            'satuan'            => $request->satuan,
            'lead_time_hari'    => $request->lead_time_hari,
            'tipe_safety_stock' => $request->tipe_safety_stock,
            'safety_stock'      => $request->safety_stock ?? 0,
        ]);

        // Kalkulasi ROP & EOQ Dinamis setelah update
        $inventoryService = new InventoryService();
        $inventoryService->recalculateForProduct($product);

        return redirect()->back()->with('success', 'Data material ' . $product->nama_barang . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->back()->with('success', 'Material berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Material tidak bisa dihapus karena sedang digunakan dalam transaksi atau riwayat sistem.');
        } catch (\Exception $e) {
            \Log::error('Persediaan Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    // =========================================================================
    // FUNGSI INTI: PENGHITUNGAN ROP & EOQ DINAMIS
    // =========================================================================
    public function recalculateAll(InventoryService $inventoryService)
    {
        $updatedCount = $inventoryService->recalculateAll();
        return redirect()->back()->with('success', "Berhasil menghitung ulang ROP & EOQ untuk {$updatedCount} barang berdasarkan data penjualan historis terbaru.");
    }

    // =========================================================================
    // API: BARCODE / SKU LOOKUP (Untuk Scanner Radar)
    // =========================================================================
    public function barcodeLookup($code)
    {
        $product = Product::where('barcode', $code)->orWhere('sku', $code)->first();

        if (!$product) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found'       => true,
            'id'          => $product->id,
            'sku'         => $product->sku,
            'nama_barang' => $product->nama_barang,
            'barcode'     => $product->barcode,
            'harga_jual'  => $product->harga_jual,
            'harga_beli'  => $product->harga_beli,
            'stok'        => $product->stok,
            'satuan'      => $product->satuan,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;

class SearchController extends Controller
{
    /**
     * FUNGSI PENCARIAN GLOBAL
     * Menarik data dari 3 tabel sekaligus berdasarkan keyword yang diketik.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        // Jika user cuma menekan 'Enter' tanpa mengetik apa-apa, kembalikan ke halaman asal.
        if (empty(trim($keyword))) {
            return redirect()->back();
        }

        // 1. CARI DI TABEL PRODUK (Persediaan)
        // Jika nanti muncul error "Unknown column 'sku'", silakan ganti kata 'sku' di bawah menjadi 'kode'
        $products = Product::with('kategori')
            ->where('nama_barang', 'LIKE', "%{$keyword}%")
            ->orWhere('sku', 'LIKE', "%{$keyword}%")
            ->orWhere('barcode', 'LIKE', "%{$keyword}%")
            ->get();

        // 2. CARI DI TABEL SUPPLIER
        // PERBAIKAN: Menggunakan nama_pic sesuai dengan struktur database aslimu
        $suppliers = Supplier::where('nama_supplier', 'LIKE', "%{$keyword}%")
            ->orWhere('nama_pic', 'LIKE', "%{$keyword}%")
            ->orWhere('alamat', 'LIKE', "%{$keyword}%")
            ->get();

        // 3. CARI DI TABEL TRANSAKSI (Barang Masuk / Keluar / PO)
        $transactions = Transaction::where('no_transaksi', 'LIKE', "%{$keyword}%")
            ->orWhere('no_referensi', 'LIKE', "%{$keyword}%")
            ->orWhere('catatan', 'LIKE', "%{$keyword}%")
            ->orWhere('tujuan', 'LIKE', "%{$keyword}%")
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika permintaan dari AJAX (Live Search), kembalikan data JSON (dibatasi sedikit agar dropdown tidak kepanjangan)
        if ($request->ajax()) {
            return response()->json([
                'products' => $products->take(4),
                'suppliers' => $suppliers->take(3),
                'transactions' => $transactions->take(3)
            ]);
        }

        // Lempar datanya ke halaman hasil pencarian (search_results.blade.php)
        return view('search_results', compact(
            'keyword', 
            'products', 
            'suppliers', 
            'transactions'
        ));
    }
}
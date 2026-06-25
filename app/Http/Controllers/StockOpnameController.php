<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreStockOpnameRequest;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        // Data untuk Form Input Opname
        $products = Product::orderBy('nama_barang', 'asc')->get();
        $categories = \App\Models\Kategori::orderBy('nama_kategori', 'asc')->get();
        
        // Data untuk Riwayat/Tabel Opname
        $stockOpnames = StockOpname::with(['pembuat', 'penyetuju', 'details.product'])
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('laporan_stock_opname', compact('products', 'categories', 'stockOpnames'));
    }

    public function store(StoreStockOpnameRequest $request)
    {
        DB::beginTransaction();
        try {
            // 1. Buat Header Draft Opname
            $noOpname = 'SO-' . Carbon::now()->format('YmdHis');
            
            $opname = StockOpname::create([
                'no_opname' => $noOpname,
                'tanggal' => Carbon::today(),
                'periode' => $request->periode,
                'status' => 'pending_approval',
                'created_by' => Auth::id(),
            ]);

            // 2. Load semua produk sekali aja (hindari N+1 di loop)
            $allProducts = Product::whereIn('id', $request->product_id)->get()->keyBy('id');

            // 3. Simpan Detail Opname (Hanya barang yang diinput fisiknya)
            foreach ($request->product_id as $index => $pid) {
                $fisik = $request->stok_fisik[$index];
                
                // Pastikan input fisik tidak kosong (karena array hidden dikirim semua)
                if ($fisik !== null && $fisik !== '') {
                    $product = $allProducts->get($pid);
                    if ($product) {
                        $selisih = (int)$fisik - $product->stok;
                        $nilaiSelisih = $selisih * $product->harga_beli;

                        StockOpnameDetail::create([
                            'stock_opname_id' => $opname->id,
                            'product_id' => $product->id,
                            'stok_sistem' => $product->stok,
                            'stok_fisik' => (int)$fisik,
                            'selisih' => $selisih,
                            'nilai_selisih' => $nilaiSelisih,
                            'keterangan' => $request->keterangan[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('stock_opname')->with('success', 'Draft Stock Opname berhasil diajukan dan menunggu persetujuan.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stock Opname Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    // ========================================================
    // INTEGRASI LAPORAN MUTASI (APPROVAL)
    // ========================================================
    public function approve($id)
    {
        $opname = StockOpname::with('details.product')->findOrFail($id);

        if ($opname->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Opname ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Kelompokkan data selisih
            $itemsMasuk = [];
            $itemsKeluar = [];
            $totalNilaiMasuk = 0;
            $totalNilaiKeluar = 0;

            foreach ($opname->details as $detail) {
                // 1. UPDATE STOK MASTER (Sinkronisasi)
                if ($detail->product) {
                    $detail->product->update(['stok' => $detail->stok_fisik]);
                }

                // 2. Pisahkan untuk integrasi Laporan Mutasi (Transactions)
                if ($detail->selisih > 0) {
                    $itemsMasuk[] = $detail;
                    $totalNilaiMasuk += abs($detail->nilai_selisih);
                } elseif ($detail->selisih < 0) {
                    $itemsKeluar[] = $detail;
                    $totalNilaiKeluar += abs($detail->nilai_selisih);
                }
            }

            // 3. Catat Barang Masuk Gaib (Selisih Positif)
            if (count($itemsMasuk) > 0) {
                $trxMasuk = Transaction::create([
                    'jenis_transaksi' => 'masuk',
                    'no_transaksi' => 'ADJ-IN-' . time(),
                    'tanggal' => Carbon::now(),
                    'tujuan' => 'Penyesuaian Sistem',
                    'kategori_keluar' => 'Stock Opname', // Memanfaatkan kolom yang ada
                    'catatan' => 'Penambahan stok dari hasil Stock Opname: ' . $opname->no_opname,
                    'total_nilai' => $totalNilaiMasuk,
                    'status_pembayaran' => 'Lunas', // Bukan hutang
                    'user_id' => Auth::id()
                ]);

                foreach ($itemsMasuk as $item) {
                    TransactionItem::create([
                        'transaction_id' => $trxMasuk->id,
                        'product_id' => $item->product_id,
                        'qty' => abs($item->selisih),
                    ]);
                }
            }

            // 4. Catat Barang Keluar Gaib (Selisih Negatif)
            if (count($itemsKeluar) > 0) {
                $trxKeluar = Transaction::create([
                    'jenis_transaksi' => 'keluar',
                    'no_transaksi' => 'ADJ-OUT-' . time(),
                    'tanggal' => Carbon::now(),
                    'tujuan' => 'Penyesuaian Sistem',
                    'kategori_keluar' => 'Stock Opname',
                    'catatan' => 'Pengurangan stok dari hasil Stock Opname: ' . $opname->no_opname,
                    'total_nilai' => $totalNilaiKeluar,
                    'status_pembayaran' => 'Lunas',
                    'user_id' => Auth::id()
                ]);

                foreach ($itemsKeluar as $item) {
                    TransactionItem::create([
                        'transaction_id' => $trxKeluar->id,
                        'product_id' => $item->product_id,
                        'qty' => abs($item->selisih),
                    ]);
                }
            }

            // 5. Ubah Status Opname
            $opname->update([
                'status' => 'approved',
                'approved_by' => Auth::id()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Stock Opname berhasil disetujui! Stok master telah diupdate dan mutasi penyesuaian telah dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stock Opname Approve Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses persetujuan. Silakan coba lagi.');
        }
    }

    public function reject(Request $request, $id)
    {
        $opname = StockOpname::findOrFail($id);
        
        $opname->update([
            'status' => 'rejected',
            'approved_by' => Auth::id()
        ]);
        
        // Opsional: Simpan alasan_tolak jika ada kolomnya di DB
        // $opname->update(['alasan_tolak' => $request->alasan_tolak]);

        return redirect()->back()->with('success', 'Laporan Stock Opname berhasil ditolak / dibatalkan.');
    }
}
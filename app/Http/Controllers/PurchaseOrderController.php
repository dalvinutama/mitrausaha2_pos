<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\PurchaseOrderCreated;
use App\Events\PurchaseOrderApproved;
use App\Events\PurchaseOrderRejected;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        // 1. Ambil data Riwayat PO beserta detail supplier, pembuatnya, dan item pesanannya
        $purchaseOrders = Transaction::with(['supplier', 'user', 'items'])
                        ->where('jenis_transaksi', 'po')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 2. Siapkan Master Data untuk form pembuatan PO (Khusus Admin)
        $suppliers = Supplier::where('status', 'Aktif')->get();
        $products = Product::orderBy('nama_barang', 'asc')->get();
        
        // 3. Generate Nomor PO Otomatis
        $autoNumber = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);

        return view('purchase_order', compact('purchaseOrders', 'suppliers', 'products', 'autoNumber'));
    }

    // Fungsi untuk Admin Keuangan membuat Draft PO
    public function store(StorePurchaseOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            // Generate nomor PO anti-collision
            $todayPoCount = Transaction::where('no_transaksi', 'like', 'PO-' . date('Ymd') . '-%')->count();
            $noTransaksi = 'PO-' . date('Ymd') . '-' . str_pad($todayPoCount + 1, 4, '0', STR_PAD_LEFT);

            // Buat KOP Surat PO
            $transaksi = Transaction::create([
                'no_transaksi'    => $noTransaksi,
                'jenis_transaksi' => 'po',
                'tanggal'         => $request->tanggal,
                'supplier_id'     => $request->supplier_id,
                'catatan'         => $request->catatan,
                'user_id'         => Auth::id(), // Pencatat (Admin)
                'status'          => 'pending',  // PENTING: Status default menunggu persetujuan Owner
            ]);

            $total_nilai = 0;

            // Masukkan rincian barang yang mau dipesan
            foreach ($request->product_id as $key => $product_id) {
                $qty = $request->qty[$key];
                $price = $request->price[$key] ?? 0;
                
                if(empty($qty) || $qty <= 0) continue;

                $subtotal = $qty * $price;
                $total_nilai += $subtotal;

                TransactionItem::create([
                    'transaction_id' => $transaksi->id,
                    'product_id'     => $product_id,
                    'qty'            => $qty,
                    'harga_satuan'   => $price,
                    'subtotal'       => $subtotal,
                ]);
                
                // CATATAN: Pembuatan PO TIDAK menambah stok. Stok baru bertambah di modul "Barang Masuk"
            }

            // Fitur Auto-Approve (Delegation of Authority): Limit Rp 5.000.000
            $status_po = ($total_nilai <= config('bisnis.po_auto_approve_limit')) ? 'approved' : 'pending';

            $transaksi->update([
                'total_nilai' => $total_nilai,
                'status' => $status_po
            ]);
            
            DB::commit(); 

            // Kirim notifikasi email ke semua Owner via Queue
            try {
                // Ambil semua email akun dengan role 'owner' yang valid
                $ownerEmails = \App\Models\User::where('role', 'owner')
                                               ->whereNotNull('email')
                                               ->where('email', '!=', '')
                                               ->pluck('email')
                                               ->toArray();
                
                if (!empty($ownerEmails)) {
                    \Illuminate\Support\Facades\Mail::to($ownerEmails)
                        ->queue(new \App\Mail\PurchaseOrderNotification($transaksi));
                }
            } catch (\Exception $e) {
                // Abaikan jika email gagal agar tidak menggagalkan proses simpan
                \Log::error('Gagal mengirim email PO: ' . $e->getMessage());
            }

            PurchaseOrderCreated::dispatch($transaksi);

            if ($status_po === 'approved') {
                return redirect()->back()->with('success', 'Purchase Order berhasil dibuat dan OTOMATIS DISETUJUI (Total di bawah Limit Rp 5.000.000).');
            } else {
                return redirect()->back()->with('success', 'Draft Purchase Order berhasil dibuat! Menunggu persetujuan Owner (Total melebihi Limit).');
            }

        } catch (\Exception $e) {
            DB::rollBack(); 
            \Log::error('PO Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat Purchase Order. Silakan coba lagi.');
        }
    }

    // Fungsi Khusus Owner untuk Menyetujui (Approve) PO
    public function update(Request $request, $id)
    {
        $transaksi = Transaction::findOrFail($id);
        
        // Pastikan hanya PO berstatus pending yang bisa diapprove
        if ($transaksi->status !== 'pending') {
            return redirect()->back()->with('error', 'Status PO sudah berubah, tidak dapat diproses lagi.');
        }

        // Ubah status menjadi disetujui
        $transaksi->update([
            'status' => 'approved',
        ]);

        PurchaseOrderApproved::dispatch($transaksi);

        return redirect()->back()->with('success', 'Purchase Order Nomor '.$transaksi->no_transaksi.' berhasil disetujui!');
    }
    // Fungsi Khusus Owner untuk Menolak (Reject) PO
    public function reject(Request $request, $id)
    {
        $transaksi = Transaction::findOrFail($id);
        
        if ($transaksi->status !== 'pending') {
            return redirect()->back()->with('error', 'Status PO sudah berubah, tidak dapat diproses lagi.');
        }

        $transaksi->update([
            'status' => 'rejected',
        ]);

        PurchaseOrderRejected::dispatch($transaksi);

        return redirect()->back()->with('success', 'Purchase Order Nomor '.$transaksi->no_transaksi.' berhasil ditolak.');
    }

    // Fungsi untuk Cetak PO
    public function print($id)
    {
        $po = Transaction::with(['supplier', 'user', 'items.product'])->findOrFail($id);
        
        // Pastikan hanya mencetak PO (opsional bisa dibatasi hanya approved)
        if ($po->jenis_transaksi !== 'po') {
            abort(404);
        }

        return view('purchase_order_print', compact('po'));
    }
    // Fungsi untuk API Rekomendasi EOQ
    public function getRecommendations()
    {
        // Cari ID produk yang sedang dalam PO Pending agar tidak ditarik ganda (Double Order)
        $pendingProductIds = TransactionItem::whereHas('transaction', function($q) {
            $q->where('jenis_transaksi', 'po')->where('status', 'pending');
        })->pluck('product_id')->toArray();

        // Cari barang yang stoknya menipis (<= ROP) dan memang layak dibeli (EOQ > 0)
        // Dan JANGAN rekap barang yang sedang pending di PO
        $recommendedProducts = Product::whereColumn('stok', '<=', 'reorder_point')
                                    ->where('eoq', '>', 0)
                                    ->whereNotIn('id', $pendingProductIds)
                                    ->get(['id', 'nama_barang', 'sku', 'satuan', 'harga_beli', 'eoq', 'stok']);

        if ($recommendedProducts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Semua stok masih dalam batas aman. Tidak ada rekomendasi.'
            ]);
        }

        // Tambahkan informasi supplier terakhir untuk referensi user
        $recommendedProducts->map(function ($product) {
            $lastPoItem = TransactionItem::where('product_id', $product->id)
                ->whereHas('transaction', function($q) {
                    $q->whereIn('jenis_transaksi', ['po', 'masuk'])
                      ->whereNotNull('supplier_id');
                })
                ->with('transaction.supplier')
                ->orderBy('created_at', 'desc')
                ->first();

            $product->last_supplier = $lastPoItem && $lastPoItem->transaction && $lastPoItem->transaction->supplier 
                ? $lastPoItem->transaction->supplier->nama_supplier 
                : '-';
                
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $recommendedProducts
        ]);
    }
}
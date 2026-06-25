<?php

namespace App\Http\Controllers;

use App\Events\StockInCreated;
use App\Http\Requests\StoreStokMasukRequest;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index()
    {
        // 1. Ambil Riwayat Barang Masuk
        $transaksiMasuk = Transaction::with(['supplier', 'user'])
                        ->where('jenis_transaksi', 'masuk')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 2. Data untuk pilihan di Form Tambah
        $suppliers = Supplier::where('status', 'Aktif')->get();
        $products = Product::orderBy('nama_barang', 'asc')->get();

        return view('stok_masuk', compact('transaksiMasuk', 'suppliers', 'products'));
    }

    public function store(StoreStokMasukRequest $request)
    {
        // Mulai Mode Aman (Safety Net Database)
        DB::beginTransaction();

        try {
            // Load supplier sekali di awal (hindari N+1 di loop)
            $supplier = $request->supplier_id ? Supplier::find($request->supplier_id) : null;
            $namaSupplier = $supplier ? $supplier->nama_supplier : 'Supplier';

            // 2. Buat KOP SURAT / Nota Utama Transaksi Masuk
            $todayBmCount = Transaction::where('no_transaksi', 'like', 'BM-' . date('Ymd') . '-%')->count();
            $no_transaksi = 'BM-' . date('Ymd') . '-' . str_pad($todayBmCount + 1, 4, '0', STR_PAD_LEFT);
            $status_pembayaran = ($request->tipe_pembayaran == 'tunai') ? 'lunas' : 'belum_lunas';
            
            $transaksi = Transaction::create([
                'no_transaksi'    => $no_transaksi,
                'jenis_transaksi' => 'masuk',
                'tanggal'         => $request->tanggal,
                'supplier_id'     => $request->supplier_id,
                'user_id'         => Auth::id(), 
                'no_referensi'    => $request->no_referensi,
                'catatan'         => $request->catatan,
                'status'          => 'selesai', 
                'tipe_pembayaran' => $request->tipe_pembayaran,
                'status_pembayaran'=> $status_pembayaran,
                'po_id'           => $request->po_id, // Tautkan ke PO jika ada
            ]);

            $total_nilai_belanja = 0;
            $isPoFullyReceived = true; // Asumsi awal PO terpenuhi

            // Jika ada PO, ambil data PO
            $po_transaction = null;
            if ($request->filled('po_id')) {
                $po_transaction = Transaction::with('items')->find($request->po_id);
            }

            // 3. LOOPING: Masukkan Barang Satu Per Satu
            foreach ($request->product_id as $key => $product_id) {
                $qty = $request->qty[$key];
                $qty_rusak = isset($request->qty_rusak[$key]) ? $request->qty_rusak[$key] : 0;
                $harga_input = $request->price[$key]; // Menggunakan harga dari form, bukan harga master (penting untuk barang bonus)
                
                if(empty($qty) || $qty <= 0) continue;

                $product = Product::findOrFail($product_id);
                $subtotal = $qty * $harga_input; 
                $total_nilai_belanja += $subtotal;

                // A. Catat barang ini masuk ke Nota Pembelian (Sesuai tagihan utuh)
                TransactionItem::create([
                    'transaction_id' => $transaksi->id,
                    'product_id'     => $product->id,
                    'qty'            => $qty,
                    'harga_satuan'   => $harga_input,
                    'subtotal'       => $subtotal,
                ]);

                // Update PO Item qty_diterima jika ditautkan ke PO
                if ($po_transaction) {
                    $poItem = $po_transaction->items->where('product_id', $product->id)->first();
                    if ($poItem) {
                        $poItem->update([
                            'qty_diterima' => $poItem->qty_diterima + $qty
                        ]);
                    }
                }

                // B. UPDATE STOK (Hanya tambahkan barang yang BAGUS ke rak gudang)
                $qty_bagus = $qty - $qty_rusak;
                $product->update([
                    'stok' => $product->stok + $qty_bagus
                ]);

                // C. JIKA ADA BARANG RUSAK, OTOMATIS BUAT RIWAYAT RETUR!
                if ($qty_rusak > 0) {
                    // Buat otomatis nota barang keluar untuk diretur
                    $trxRusak = Transaction::create([
                        'no_transaksi'    => 'RTR-' . date('Ymd') . '-' . substr(uniqid(), -6),
                        'jenis_transaksi' => 'keluar',
                        'tanggal'         => $request->tanggal,
                        'user_id'         => Auth::id(),
                        'kategori_keluar' => 'Rusak', // Kata kunci ini yang dibaca oleh Dashboard!
                        'tujuan'          => 'Retur ke ' . $namaSupplier,
                        'catatan'         => "Barang rusak/cacat saat penerimaan faktur: " . $no_transaksi,
                        'status'          => 'selesai',
                        'total_nilai'     => $qty_rusak * $product->harga_beli
                    ]);

                    // Masukkan detail barang yang rusaknya
                    TransactionItem::create([
                        'transaction_id' => $trxRusak->id,
                        'product_id'     => $product->id,
                        'qty'            => $qty_rusak,
                        'harga_satuan'   => $harga_input,
                        'subtotal'       => $qty_rusak * $harga_input,
                    ]);
                }
            }

            // 4. Simpan Total Belanja ke Kop Surat Nota
            // Cek apakah Tagihan Fisik > Tagihan PO
            $catatanWarning = "";
            if ($po_transaction && $total_nilai_belanja > $po_transaction->total_nilai) {
                $catatanWarning = " [⚠️ OVERBUDGET - TOTAL BM MELEBIHI TOTAL PO]";
            }
            $transaksi->update([
                'total_nilai' => $total_nilai_belanja,
                'catatan' => $transaksi->catatan . $catatanWarning
            ]);

            // =========================================================
            // 5. FITUR AUTO-SELESAI PO (PARTIAL FULFILLMENT)
            // =========================================================
            if ($po_transaction) {
                // Cek ulang semua item di PO tersebut apakah qty_diterima sudah memenuhi target qty
                $isPoFullyReceived = true;
                foreach ($po_transaction->items as $item) {
                    if ($item->qty_diterima < $item->qty) {
                        $isPoFullyReceived = false;
                        break;
                    }
                }

                if ($isPoFullyReceived) {
                    $po_transaction->update([
                        'status' => 'selesai',
                        'catatan' => $po_transaction->catatan . " [Ditutup otomatis oleh BM: {$no_transaksi}]"
                    ]);
                }
            }

            // =========================================================
            // 6. FITUR CATAT HUTANG & PEMBAYARAN AWAL
            // =========================================================
            if ($request->tipe_pembayaran == 'tempo' && $request->filled('tanggal_tempo')) {
                $tglTempoFormatted = Carbon::parse($request->tanggal_tempo)->format('d/m/Y');
                $catatanBaru = $transaksi->catatan . " [Pembayaran TEMPO. Jatuh Tempo: " . $tglTempoFormatted . "]";
                
                $transaksi->update([
                    'catatan' => trim($catatanBaru)
                ]);
            }

            // Upload Bukti Pembayaran (jika ada) — gunakan hashName agar aman dari spoofed extension
            $bukti_path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = $file->hashName();
                $file->move(public_path('uploads/payments'), $filename);
                $bukti_path = 'uploads/payments/' . $filename;
            }

            // Catat ke Buku Kas (Riwayat Pembayaran)
            if ($request->tipe_pembayaran == 'tunai') {
                // Tunai = Lunas 100%
                \App\Models\TransactionPayment::create([
                    'transaction_id' => $transaksi->id,
                    'nominal' => $total_nilai_belanja,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'bukti_pembayaran' => $bukti_path,
                    'tanggal_bayar' => $request->tanggal,
                    'user_id' => Auth::id(),
                ]);
            } else if ($request->tipe_pembayaran == 'tempo') {
                // Tempo = Catat DP (jika ada)
                if ($request->filled('dp_nominal') && $request->dp_nominal > 0) {
                    // Validasi: DP tidak boleh melebihi total belanja
                    if ($request->dp_nominal >= $total_nilai_belanja) {
                        throw new \Exception("Nominal DP tidak boleh lebih besar atau sama dengan total belanja jika memilih Jatuh Tempo.");
                    }

                    \App\Models\TransactionPayment::create([
                        'transaction_id' => $transaksi->id,
                        'nominal' => $request->dp_nominal,
                        'metode_pembayaran' => $request->metode_pembayaran,
                        'bukti_pembayaran' => $bukti_path,
                        'tanggal_bayar' => $request->tanggal,
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            // Kunci permanen data di database
            DB::commit(); 

            StockInCreated::dispatch($transaksi);

            return redirect()->back()->with('success', "Surat Jalan {$no_transaksi} berhasil dicatat. Stok gudang sudah bertambah otomatis!");

        } catch (\Exception $e) {
            DB::rollBack(); 
            \Log::error('Stok Masuk Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi. Silakan periksa kembali inputan Anda.');
        }
    }

    public function getPosBySupplier($supplier_id)
    {
        $poAktif = Transaction::whereIn('jenis_transaksi', ['po', 'purchase_order', 'PO', 'Purchase Order', 'pesanan'])
            ->where('supplier_id', $supplier_id)
            ->whereIn('status', ['pending', 'approved', 'waiting'])
            ->orderBy('tanggal', 'desc')
            ->get();

        $formattedPos = $poAktif->map(function ($po) {
            $formattedDate = Carbon::parse($po->tanggal)->translatedFormat('d F Y');
            $formattedTotal = 'Rp ' . number_format($po->total_nilai, 0, ',', '.');
            return [
                'id' => $po->id,
                'text' => "{$po->no_transaksi} — {$formattedDate} ({$formattedTotal})"
            ];
        });

        return response()->json($formattedPos);
    }

    public function getPoItems($id)
    {
        $items = TransactionItem::with('product')
            ->where('transaction_id', $id)
            ->get();
        
        $formatted = $items->map(function ($item) {
            $sisa = $item->qty - $item->qty_diterima;
            // Hanya kembalikan nilai sisa yang valid
            return [
                'product_id' => $item->product_id,
                'sku' => $item->product->sku ?? '',
                'nama_barang' => $item->product->nama_barang ?? '',
                'qty_target' => $item->qty,
                'qty_diterima' => $item->qty_diterima,
                'sisa' => $sisa > 0 ? $sisa : 0,
                'harga_satuan' => $item->harga_satuan
            ];
        });

        return response()->json($formatted);
    }
}
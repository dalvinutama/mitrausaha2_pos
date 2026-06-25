<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\StockOutCreated;
use App\Http\Requests\StoreStokKeluarRequest;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\KategoriOutbound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokKeluarController extends Controller
{
    public function index()
    {
        // 1. Ambil Riwayat Barang Keluar (Terakhir 10 untuk efisiensi di tabel riwayat)
        $transaksiKeluar = Transaction::with(['user'])
                        ->where('jenis_transaksi', 'keluar')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // 2. Ambil data produk yang stoknya > 0 + sertakan harga jualnya & kategori
        $products = Product::with('kategori')
                        ->where('stok', '>', 0)
                        ->orderBy('nama_barang', 'asc')
                        ->get();

        // 3. Ambil Kategori Pengeluaran Dinamis
        $kategoriOutbounds = KategoriOutbound::orderBy('nama_kategori', 'asc')->get();
        
        // Ambil Kategori Produk untuk filter POS
        $kategoriProduk = \App\Models\Kategori::orderBy('nama_kategori', 'asc')->get();
        
        // 4. Generate Auto Number untuk tampilan UI
        $autoNumber = 'BK-' . date('Ymd') . '-' . rand(1000, 9999);

        return view('stok_keluar', compact('transaksiKeluar', 'products', 'kategoriOutbounds', 'kategoriProduk', 'autoNumber'));
    }

    /**
     * Fitur Tambah Kategori Pengeluaran secara instan (AJAX SweetAlert)
     * Menggunakan simpan manual agar aman dari error $fillable
     */
    public function storeKategori(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:100'
            ]);

            $namaBaru = strtoupper(trim($request->nama_kategori));

            // 1. Cek apakah kategori sudah ada di database
            $kategori = KategoriOutbound::where('nama_kategori', $namaBaru)->first();

            // 2. Jika belum ada, buat baru menggunakan cara manual
            if (!$kategori) {
                $kategori = new KategoriOutbound();
                $kategori->nama_kategori = $namaBaru;
                $kategori->save();
            }

            // 3. Kembalikan respons sukses ke JavaScript
            return response()->json([
                'success' => true,
                'id'      => $kategori->id,
                'nama'    => $kategori->nama_kategori
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Nama Kategori terlalu panjang atau tidak valid.'], 422);
        } catch (\Exception $e) {
            \Log::error('Kategori Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.'], 500);
        }
    }

    public function updateKategori(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:100'
            ]);

            $kategori = KategoriOutbound::findOrFail($id);
            $namaBaru = strtoupper(trim($request->nama_kategori));

            // Cek duplikasi
            $exists = KategoriOutbound::where('nama_kategori', $namaBaru)->where('id', '!=', $id)->exists();
            if ($exists) {
                return response()->json(['error' => 'Nama kategori sudah digunakan.'], 422);
            }

            $kategori->update(['nama_kategori' => $namaBaru]);

            return response()->json([
                'success' => true,
                'id'      => $kategori->id,
                'nama'    => $kategori->nama_kategori
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Kategori Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.'], 500);
        }
    }

    public function destroyKategori($id)
    {
        try {
            $kategori = KategoriOutbound::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus.'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Kategori Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.'], 500);
        }
    }

    public function store(StoreStokKeluarRequest $request)
    {
        // Mulai Mode Aman (Safety Net Database)
        DB::beginTransaction();

        try {
            // 2. Generate nomor transaksi anti-collision
            $todayBkCount = \App\Models\Transaction::where('no_transaksi', 'like', 'BK-' . date('Ymd') . '-%')->count();
            $noTransaksi = 'BK-' . date('Ymd') . '-' . str_pad($todayBkCount + 1, 4, '0', STR_PAD_LEFT);

            // 3. Buat KOP SURAT Nota Pengeluaran
            $transaksi = Transaction::create([
                'no_transaksi'    => $noTransaksi,
                'jenis_transaksi' => 'keluar',
                'tanggal'         => $request->outbound_date,
                'kategori_keluar' => $request->kategori_keluar, // Berisi Nama Kategori
                'tujuan'          => $request->tujuan ?: 'Ambil Sendiri (Walk-In)',
                'no_referensi'    => $request->reference_number,
                'catatan'         => $request->notes,
                'user_id'         => Auth::id(),
                'status'          => 'selesai',
            ]);

            $total_nilai_keluar = 0;

            // 3. LOOPING: Masukkan Barang ke Nota & Kurangi Stok
            foreach ($request->product_id as $key => $product_id) {
                $qty = $request->qty[$key];
                $price = $request->price[$key] ?? 0;
                
                if(empty($qty) || $qty <= 0) continue;

                // Lock record agar tidak ada race condition stok
                $product = Product::lockForUpdate()->findOrFail($product_id);

                // --- PENGECEKAN STOK: Cegah Stok Minus ---
                if ($product->stok < $qty) {
                    throw new \Exception("Stok '{$product->nama_barang}' tidak cukup! Sisa gudang: {$product->stok}");
                }

                $subtotal = $qty * $price;
                $total_nilai_keluar += $subtotal;

                // A. Catat rincian
                TransactionItem::create([
                    'transaction_id' => $transaksi->id,
                    'product_id'     => $product->id,
                    'qty'            => $qty,
                    'harga_satuan'   => $price,
                    'subtotal'       => $subtotal,
                ]);

                // B. UPDATE STOK: Kurangi stok asli
                $newStok = $product->stok - $qty;
                $product->update([
                    'stok' => $newStok
                ]);

                // --- SENSOR STOK MENIPIS (TAHAP 2) ---
                if ($newStok <= $product->reorder_point) {
                    $cacheKey = 'low_stock_notified_' . $product->id;
                    // Cek apakah sudah pernah diinfokan dalam 24 jam terakhir
                    if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                        try {
                            $ownerEmails = \App\Models\User::whereIn('role', ['owner', 'admin'])
                                ->whereNotNull('email')
                                ->pluck('email')
                                ->toArray();

                            if (!empty($ownerEmails)) {
                                \Illuminate\Support\Facades\Mail::to($ownerEmails)
                                    ->queue(new \App\Mail\LowStockNotification($product));
                                
                                // Kunci memori selama 24 jam agar tidak spam
                                \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addHours(24));
                            }
                        } catch (\Exception $e) {
                            \Log::error('Gagal mengirim email Low Stock: ' . $e->getMessage());
                        }
                    }
                }
                // ------------------------------------
            }

            // 4. Update Total Nilai Akhir
            $diskon = $request->diskon ? str_replace('.', '', $request->diskon) : 0;
            $transaksi->update([
                'total_nilai' => $total_nilai_keluar - $diskon,
                'diskon' => $diskon
            ]);

            DB::commit(); 

            StockOutCreated::dispatch($transaksi);

            return redirect()->route('stok_keluar.print', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollBack(); 
            \Log::error('Stok Keluar Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses transaksi. Silakan periksa kembali inputan Anda.');
        }
    }

    public function print($id)
    {
        $transaction = \App\Models\Transaction::with(['items.product', 'user', 'supplier'])
                            ->where('jenis_transaksi', 'keluar')
                            ->findOrFail($id);
                            
        // Ambil data profil toko dari store_profiles
        $toko = \DB::table('store_profiles')->where('is_active', true)->first()
                ?? \DB::table('store_profiles')->first();

        return view('stok_keluar_print', compact('transaction', 'toko'));
    }
}
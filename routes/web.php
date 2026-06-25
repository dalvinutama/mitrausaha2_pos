<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdvancedAnalyticsController; 
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MessageController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokMasukController; 
use App\Http\Controllers\StokKeluarController;
use App\Http\Controllers\PurchaseOrderController; 
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KategoriController; 
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockOpnameController; // Controller Baru
use App\Http\Controllers\AiConfigController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); 
});

Route::get('/debug-rop', function () {
    $product = App\Models\Product::where('sku', 'ELK-006')->first();
    $inventoryService = new App\Services\InventoryService();
    return response()->json($inventoryService->recalculateForProduct($product));
});

// ==========================================================
// ROUTE GANTI BAHASA (PUBLIC - TIDAK PERLU LOGIN)
// ==========================================================
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
        \Illuminate\Support\Facades\App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// ==========================================================
// GROUP UTAMA: WAJIB LOGIN (AUTH)
// ==========================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // SEMUA ROLE BISA AKSES DASHBOARD, PENCARIAN, & PESAN
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/panduan', [DashboardController::class, 'walkthrough'])->name('panduan');
    Route::get('/dashboard/advanced-analytics', [AdvancedAnalyticsController::class, 'generateReport'])->name('analytics.generate');
    Route::get('/search', [SearchController::class, 'index'])->name('search')->middleware('throttle:30,1'); 
    Route::get('/api/barcode-lookup/{code}', [PersediaanController::class, 'barcodeLookup'])->name('barcode.lookup');
    
    // FITUR PESAN / CHAT INTERNAL (AJAX & REALTIME)
    Route::get('/pesan/conversations', [MessageController::class, 'getConversations'])->name('pesan.conversations');
    Route::post('/pesan/kirim', [MessageController::class, 'store'])->name('pesan.store'); 
    Route::put('/pesan/{id}', [MessageController::class, 'update'])->name('pesan.update'); 
    Route::delete('/pesan/{id}', [MessageController::class, 'destroy'])->name('pesan.destroy'); 
    Route::post('/pesan/mark-as-read', [MessageController::class, 'markAsRead'])->name('pesan.mark_read'); 
    Route::post('/pesan/sync', [MessageController::class, 'sync'])->name('pesan.sync'); 
    Route::post('/pesan/clear-global', [MessageController::class, 'clearGlobalChat'])->name('pesan.clear_global');

    // ROUTE PROFILE (Semua bisa ubah profil sendiri)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ==========================================================
    // 1. HAK AKSES "READ" (MELIHAT HALAMAN / GET)
    // ==========================================================
    
    // Semua Role bisa lihat Persediaan dan Stok Keluar (Penjualan)
    Route::middleware(['role:owner,admin,penjualan,gudang,kasir,pengiriman'])->group(function () {
        Route::get('/persediaan', [PersediaanController::class, 'index'])->name('persediaan');
        Route::get('/stok_keluar', [StokKeluarController::class, 'index'])->name('stok_keluar');
    });

    // Akses Lihat Kategori
    Route::middleware(['role:owner,admin,penjualan,gudang'])->group(function () {
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    });

    // Akses Lihat Supplier, Barang Masuk, Purchase Order, dan Stock Opname
    Route::middleware(['role:owner,admin,gudang'])->group(function () {
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
        Route::get('/stok_masuk', [StokMasukController::class, 'index'])->name('stok_masuk');
        Route::get('/get-pos-by-supplier/{supplier_id}', [StokMasukController::class, 'getPosBySupplier']);
        Route::get('/get-po-items/{id}', [StokMasukController::class, 'getPoItems']);

        Route::get('/stock_opname', [StockOpnameController::class, 'index'])->name('stock_opname'); // Fitur Baru
    });

    // Akses Laporan Khusus Strategis (Owner & Admin)
    Route::middleware(['role:owner,admin'])->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    });


    // ==========================================================
    // 2. HAK AKSES "CREATE / UPDATE / DELETE" (PROSES SIMPAN)
    // ==========================================================

    // GUDANG & ADMIN 
    Route::middleware(['role:gudang,admin'])->group(function () {
        // Route untuk mengelola Persediaan (Master Barang)
        Route::post('/persediaan/recalculate', [PersediaanController::class, 'recalculateAll'])->name('persediaan.recalculate');
        Route::post('/persediaan/simpan', [PersediaanController::class, 'store'])->name('persediaan.store');
        Route::put('/persediaan/{id}', [PersediaanController::class, 'update'])->name('persediaan.update');
        Route::delete('/persediaan/{id}', [PersediaanController::class, 'destroy'])->name('persediaan.destroy');
        
        // Kategori, Stok Masuk & Draft Stock Opname
        Route::post('/kategori/simpan', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        
        // Satuan
        Route::post('/satuan/simpan', [SatuanController::class, 'store'])->name('satuan.store');
        Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
        Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

        Route::post('/stok_masuk/simpan', [StokMasukController::class, 'store'])->name('stok_masuk.store');
    });

    // OWNER, GUDANG & ADMIN (Simpan Stock Opname)
    Route::middleware(['role:owner,gudang,admin'])->group(function () {
        Route::post('/stock_opname/simpan', [StockOpnameController::class, 'store'])->name('stock_opname.store'); // Fitur Baru
    });

    // OWNER, GUDANG & ADMIN (CRUD Supplier & View Purchase Order)
    Route::middleware(['role:owner,admin,gudang'])->group(function () {
        // Route CRUD Supplier Lengkap
        Route::post('/supplier/simpan', [SupplierController::class, 'store'])->name('supplier.store');
        Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
        
        // VIEW & PRINT Purchase Order (Gudang hanya Read-Only History, Admin/Owner bisa Create)
        Route::get('/purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase_order');
        Route::get('/purchase-order/{id}/print', [PurchaseOrderController::class, 'print'])->name('purchase_order.print');
    });

    // APPROVAL TRANSAKSI, PO, & HUTANG (Owner & Admin)
    Route::middleware(['role:owner,admin'])->group(function () {
        // Create & Approve PO, API Recommendation
        Route::post('/purchase-order', [PurchaseOrderController::class, 'store'])->name('purchase_order.store');
        Route::put('/purchase-order/{id}/approve', [PurchaseOrderController::class, 'update'])->name('purchase_order.update');
        Route::put('/purchase-order/{id}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase_order.reject');
        Route::get('/api/po-recommendation', [PurchaseOrderController::class, 'getRecommendations'])->name('purchase_order.recommendation');
        
        // Approval Stock Opname
        Route::put('/stock_opname/{id}/approve', [StockOpnameController::class, 'approve'])->name('stock_opname.approve');
        Route::put('/stock_opname/{id}/reject', [StockOpnameController::class, 'reject'])->name('stock_opname.reject');

        // Hutang (Payment Ledger)
        Route::get('/hutang', [\App\Http\Controllers\HutangController::class, 'index'])->name('hutang');
        Route::post('/hutang/{id}/bayar', [\App\Http\Controllers\HutangController::class, 'bayarCicilan'])->name('hutang.bayar');
        Route::get('/hutang/payment/{id}/print', [\App\Http\Controllers\HutangController::class, 'printPayment'])->name('hutang.payment.print');
        Route::delete('/hutang/batal/{id}', [\App\Http\Controllers\HutangController::class, 'batalkanPembayaran'])->name('hutang.batal');

        // Penyelesaian Dashboard Widget
        Route::post('/dashboard/selesai-hutang/{id}', [DashboardController::class, 'selesaiHutang'])->name('dashboard.selesai_hutang');
        Route::post('/dashboard/selesai-rusak/{id}', [DashboardController::class, 'selesaiRusak'])->name('dashboard.selesai_rusak');
        Route::post('/dashboard/terima-pengganti/{id}', [DashboardController::class, 'terimaPengganti'])->name('dashboard.terima_pengganti');
    });

    // KHUSUS PENJUALAN (Input Stok Keluar & Kategori Cepat)
    Route::middleware(['role:penjualan'])->group(function () {
        Route::post('/stok_keluar/simpan', [StokKeluarController::class, 'store'])->name('stok_keluar.store');
        Route::get('/stok_keluar/{id}/print', [StokKeluarController::class, 'print'])->name('stok_keluar.print');
        Route::post('/stok_keluar/kategori', [StokKeluarController::class, 'storeKategori'])->name('stok_keluar.kategori.store');
        Route::put('/stok_keluar/kategori/{id}', [StokKeluarController::class, 'updateKategori'])->name('stok_keluar.kategori.update');
        Route::delete('/stok_keluar/kategori/{id}', [StokKeluarController::class, 'destroyKategori'])->name('stok_keluar.kategori.destroy');
    });

    // ==========================================================
    // 3. PENGATURAN SISTEM & PENGGUNA
    // ==========================================================
    
    // KHUSUS OWNER (Pengaturan Toko & Kop Surat)
    Route::middleware(['role:owner'])->group(function () {
        
        // --- ROUTE BARU UNTUK APLIKASI/SIDEBAR ---
        Route::post('/pengaturan/aplikasi', [SettingController::class, 'updateAplikasi'])->name('pengaturan.aplikasi');
        Route::put('/pengaturan/email', [SettingController::class, 'updateEmailSettings'])->name('pengaturan.email');

        // AI Config & Transparency (Owner Only)
        Route::get('/api/ai-config', [AiConfigController::class, 'getConfig']);
        Route::post('/api/ai-config', [AiConfigController::class, 'updateConfig']);

        // Profil Toko & Pengaturan
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan', [SettingController::class, 'store'])->name('pengaturan.store'); 
        Route::put('/pengaturan/{id}', [SettingController::class, 'update'])->name('pengaturan.update'); 
        Route::delete('/pengaturan/{id}', [SettingController::class, 'destroy'])->name('pengaturan.destroy'); 
        Route::post('/pengaturan/{id}/set-active', [SettingController::class, 'setActive'])->name('pengaturan.set_active');
    });

    // OWNER & ADMIN (Manajemen Karyawan / User & Audit)
    Route::middleware(['role:owner,admin'])->group(function () {
        Route::get('/audit-log', [SettingController::class, 'auditLog'])->name('audit_log');
        Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna');
        Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');
        Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('pengguna.destroy');
    });

    // ==== ROUTE PREVIEW EMAIL HEADER (TANPA SMTP) ====
    Route::get('/test-header-email', function () {
        $stokMenipis = \App\Models\Product::whereColumn('stok', '<=', 'reorder_point')->get();
        
        $hutangTempo = collect([]);
        $besok = \Carbon\Carbon::tomorrow()->format('Y-m-d');
        $transaksiTempo = \App\Models\Transaction::with('supplier')
            ->where('jenis_transaksi', 'masuk')
            ->where('catatan', 'LIKE', '%[Pembayaran TEMPO%')
            ->get();
            
        foreach($transaksiTempo as $trx) {
            preg_match('/Jatuh Tempo:\s*([^\]]+)/', $trx->catatan, $matches);
            if(isset($matches[1])) {
                try {
                    $tglTempoDb = \Carbon\Carbon::createFromFormat('d/m/Y', trim($matches[1]))->format('Y-m-d');
                    if($tglTempoDb <= $besok) {
                        $trx->tanggal_tempo = $tglTempoDb; 
                        $hutangTempo->push($trx);
                    }
                } catch (\Exception $e) {}
            }
        }

        return new \App\Mail\HeaderNotificationMail($stokMenipis, $hutangTempo);
    });

    // ==== ROUTE PREVIEW EMAIL LOW STOCK ====
    Route::get('/test-low-stock', function () {
        $product = \App\Models\Product::where('kode_barang', 'LIKE', '%Vinilex%')->first() 
                   ?? \App\Models\Product::first();
        return new \App\Mail\LowStockNotification($product);
    });

});

require __DIR__.'/auth.php';
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kategori;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\KategoriOutbound;

class SetupMasterDataSimulasi extends Command
{
    protected $signature = 'simulasi:fase1-master';
    protected $description = 'Fase 1: Tambah kategori, produk & supplier untuk simulasi';

    public function handle()
    {
        $this->info('=== FASE 1: MASTER DATA ===');

        // --- Kategori Outbound ---
        foreach (['PENJUALAN','PROYEK','INTERNAL','RETUR','HIBAH'] as $k) {
            KategoriOutbound::firstOrCreate(['nama_kategori' => $k]);
        }
        $this->info('[OK] Kategori Outbound');

        // --- Kategori Produk Baru ---
        $kats = [
            ['prefix_sku' => 'ELK', 'nama_kategori' => 'Elektrik & Kabel', 'deskripsi' => 'Kabel, stop kontak, saklar, MCB, fitting lampu'],
            ['prefix_sku' => 'SFY', 'nama_kategori' => 'Alat Safety & Tukang', 'deskripsi' => 'Helm, sepatu safety, sarung tangan, masker, meteran'],
            ['prefix_sku' => 'PLN', 'nama_kategori' => 'Plumbing & Sanitary', 'deskripsi' => 'Closet, wastafel, kran, pipa, shower'],
        ];
        $katIds = [];
        foreach ($kats as $k) {
            $kat = Kategori::firstOrCreate(['prefix_sku' => $k['prefix_sku']], $k);
            $katIds[$k['prefix_sku']] = $kat->id;
        }
        $this->info('[OK] 3 Kategori baru');

        // --- Supplier Baru ---
        $suppliers = [
            ['nama_supplier' => 'PT. Sinar Jaya Elektrik', 'alamat' => 'Jl. Nusa Indah II No.15, Pontianak', 'nama_pic' => 'Bpk. Haryono', 'no_hp' => '0812-3456-7890', 'kategori_suplai' => 'Elektrik', 'termin_default' => '30', 'status' => 'Aktif'],
            ['nama_supplier' => 'CV. Safety Mandiri Utama', 'alamat' => 'Jl. Komplek Pergudangan Siantan, Pontianak', 'nama_pic' => 'Ibu Dewi', 'no_hp' => '0856-7890-1234', 'kategori_suplai' => 'Alat Safety', 'termin_default' => '14', 'status' => 'Aktif'],
            ['nama_supplier' => 'PD. Sanitair Pontianak', 'alamat' => 'Jl. Gajah Mada No.45, Pontianak', 'nama_pic' => 'Ko Hendra', 'no_hp' => '0878-1111-2222', 'kategori_suplai' => 'Plumbing', 'termin_default' => 'Cash', 'status' => 'Aktif'],
            ['nama_supplier' => 'UD. Sumber Rejeki', 'alamat' => 'Jl. Imam Bonjol, Pontianak', 'nama_pic' => 'Bpk. Amin', 'no_hp' => '0813-5555-6666', 'kategori_suplai' => 'General Material', 'termin_default' => '30', 'status' => 'Aktif'],
        ];
        $supIds = [];
        foreach ($suppliers as $s) {
            $sup = Supplier::firstOrCreate(['nama_supplier' => $s['nama_supplier']], $s);
            $supIds[$s['kategori_suplai']] = $sup->id;
        }
        $this->info('[OK] 4 Supplier baru');

        // --- Mapping kategori ID ---
        $katMap = [];
        foreach (Kategori::all() as $k) {
            $katMap[$k->prefix_sku] = $k->id;
        }

        // --- Produk Baru ---
        $newProducts = [
            // Elektrik & Kabel (ELK)
            ['kategori_id' => $katMap['ELK'], 'sku' => 'ELK-001', 'nama_barang' => 'Kabel NYM 2x1.5mm', 'harga_beli' => 180000, 'harga_jual' => 220000, 'satuan' => 'Meter'],
            ['kategori_id' => $katMap['ELK'], 'sku' => 'ELK-002', 'nama_barang' => 'Stop Kontak Broco', 'harga_beli' => 12000, 'harga_jual' => 18000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['ELK'], 'sku' => 'ELK-003', 'nama_barang' => 'Saklar Tunggal Broco', 'harga_beli' => 10000, 'harga_jual' => 15000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['ELK'], 'sku' => 'ELK-004', 'nama_barang' => 'MCB Schneider 6A', 'harga_beli' => 35000, 'harga_jual' => 50000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['ELK'], 'sku' => 'ELK-005', 'nama_barang' => 'Fitting Lampu', 'harga_beli' => 5000, 'harga_jual' => 8000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['ELK'], 'sku' => 'ELK-006', 'nama_barang' => 'Kabel NYA 1.5mm', 'harga_beli' => 100000, 'harga_jual' => 135000, 'satuan' => 'Meter'],

            // Alat Safety & Tukang (SFY)
            ['kategori_id' => $katMap['SFY'], 'sku' => 'SFY-001', 'nama_barang' => 'Helm Proyek', 'harga_beli' => 25000, 'harga_jual' => 40000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['SFY'], 'sku' => 'SFY-002', 'nama_barang' => 'Sepatu Safety', 'harga_beli' => 85000, 'harga_jual' => 120000, 'satuan' => 'Pasang'],
            ['kategori_id' => $katMap['SFY'], 'sku' => 'SFY-003', 'nama_barang' => 'Sarung Tangan Kerja', 'harga_beli' => 8000, 'harga_jual' => 15000, 'satuan' => 'Pasang'],
            ['kategori_id' => $katMap['SFY'], 'sku' => 'SFY-004', 'nama_barang' => 'Masker Debu N95', 'harga_beli' => 15000, 'harga_jual' => 25000, 'satuan' => 'Box'],
            ['kategori_id' => $katMap['SFY'], 'sku' => 'SFY-005', 'nama_barang' => 'Meteran 5m', 'harga_beli' => 15000, 'harga_jual' => 25000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['SFY'], 'sku' => 'SFY-006', 'nama_barang' => 'Gerinda Tangan 4 inch', 'harga_beli' => 180000, 'harga_jual' => 250000, 'satuan' => 'Unit'],

            // Plumbing & Sanitary (PLN)
            ['kategori_id' => $katMap['PLN'], 'sku' => 'PLN-001', 'nama_barang' => 'Closet Duduk', 'harga_beli' => 350000, 'harga_jual' => 450000, 'satuan' => 'Unit'],
            ['kategori_id' => $katMap['PLN'], 'sku' => 'PLN-002', 'nama_barang' => 'Wastafel', 'harga_beli' => 180000, 'harga_jual' => 250000, 'satuan' => 'Unit'],
            ['kategori_id' => $katMap['PLN'], 'sku' => 'PLN-003', 'nama_barang' => 'Kran Air', 'harga_beli' => 35000, 'harga_jual' => 55000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['PLN'], 'sku' => 'PLN-004', 'nama_barang' => 'Flexible Hose', 'harga_beli' => 15000, 'harga_jual' => 25000, 'satuan' => 'Pcs'],
            ['kategori_id' => $katMap['PLN'], 'sku' => 'PLN-005', 'nama_barang' => 'Shower Set', 'harga_beli' => 60000, 'harga_jual' => 90000, 'satuan' => 'Set'],
            ['kategori_id' => $katMap['PLN'], 'sku' => 'PLN-006', 'nama_barang' => 'Pipa PVC 3/4 inch', 'harga_beli' => 22000, 'harga_jual' => 30000, 'satuan' => 'Batang'],

            // Tambahan kategori existing
            ['kategori_id' => $katMap['SMN'], 'sku' => 'SMN-005', 'nama_barang' => 'Semen SCG 50Kg', 'harga_beli' => 60000, 'harga_jual' => 70000, 'satuan' => 'Sak'],
            ['kategori_id' => $katMap['BSI'], 'sku' => 'BSI-007', 'nama_barang' => 'Besi CNP 100', 'harga_beli' => 180000, 'harga_jual' => 220000, 'satuan' => 'Batang'],
        ];

        $inserted = 0;
        foreach ($newProducts as $p) {
            $exists = Product::where('sku', $p['sku'])->exists();
            if (!$exists) {
                Product::create($p);
                $inserted++;
            }
        }
        $this->info("[OK] $inserted produk baru ditambahkan");

        $total = Product::count();
        $totalSup = Supplier::count();
        $totalKat = Kategori::count();
        $this->info("Status akhir - Produk: $total, Kategori: $totalKat, Supplier: $totalSup");
        $this->info('=== FASE 1 SELESAI ===');
    }
}

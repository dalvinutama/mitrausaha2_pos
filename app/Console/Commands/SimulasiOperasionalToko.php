<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\KategoriOutbound;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SimulasiOperasionalToko extends Command
{
    protected $signature = 'simulasi:operasional-toko';
    protected $description = 'Generate 2 tahun data operasional toko (BM & BK) natural';

    private $userGudang = 4;
    private $userPenjualan = 3;
    private $userAdmin = 2;

    private $produk = [];
    private $individu = [];
    private $proyek = [];
    private $stok = [];
    private $season = [1=>-1,2=>-1,3=>0,4=>0,5=>1,6=>1,7=>2,8=>2,9=>1,10=>0,11=>-1,12=>-2];

    public function handle()
    {
        $this->info("=== SIMULASI 2 TAHUN OPERASIONAL TOKO ===");
        $this->setupMasterData();
        $this->loadProduk();
        $this->loadPelanggan();
        $this->loadStokAwal();

        $totalBM = 0; $totalBK = 0;
        $bulanMulai = 7; // Juli 2026
        $tahunMulai = 2026;

        for ($i = 0; $i < 24; $i++) {
            $m = $bulanMulai + $i;
            $y = $tahunMulai + intval(($bulanMulai + $i - 1) / 12) - intval(($bulanMulai - 1) / 12);
            $bulan = ($m - 1) % 12 + 1;
            $tahun = $y;

            if ($bulan > 12) { $bulan -= 12; $tahun += 1; }

            $this->info("--- $bulan/$tahun ---");
            DB::beginTransaction();
            try {
                $sf = $this->season[$bulan] ?? 0;

                $jmlBM = rand(2, 3 + max(0, $sf));
                for ($b = 0; $b < $jmlBM; $b++) {
                    $this->buatBM($bulan, $tahun, $b);
                    $totalBM++;
                }

                $jmlBK = rand(10, 14) + $sf * 2;
                for ($k = 0; $k < $jmlBK; $k++) {
                    $this->buatBK($bulan, $tahun, $k);
                    $totalBK++;
                }

                DB::commit();
                $this->line("  BM: $jmlBM | BK: $jmlBK");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal bulan $bulan/$tahun: " . $e->getMessage());
                return 1;
            }
        }

        $this->info("=== SELESAI! Total BM: $totalBM | Total BK: $totalBK ===");
        return 0;
    }

    private function setupMasterData()
    {
        $kats = ['PENJUALAN', 'PROYEK', 'INTERNAL', 'RETUR', 'HIBAH'];
        foreach ($kats as $k) {
            KategoriOutbound::firstOrCreate(['nama_kategori' => $k]);
        }

        $sup = [
            ['nama_supplier' => 'CV. Baja Ringan Borneo', 'alamat' => 'Jl. Khatulistiwa Gg. Melati No.5, Pontianak', 'nama_pic' => 'Bpk. Hartono', 'no_hp' => '0812-888-9999', 'kategori_suplai' => 'Besi & Atap', 'termin_default' => '30 Hari', 'status' => 'Aktif'],
            ['nama_supplier' => 'UD. Maju Jaya Hardware', 'alamat' => 'Jl. Pahlawan No.120, Pontianak', 'nama_pic' => 'Mas Agus', 'no_hp' => '0878-555-4444', 'kategori_suplai' => 'Peralatan', 'termin_default' => '14 Hari', 'status' => 'Aktif'],
            ['nama_supplier' => 'PT. Distributor Semen Borneo', 'alamat' => 'Jl. Sutan Syahrir Km 5, Kubu Raya', 'nama_pic' => 'Bu Rina', 'no_hp' => '0853-111-2222', 'kategori_suplai' => 'Semen', 'termin_default' => '60', 'status' => 'Aktif'],
        ];
        foreach ($sup as $s) {
            Supplier::firstOrCreate(['nama_supplier' => $s['nama_supplier']], $s);
        }
    }

    private function loadProduk()
    {
        $semua = Product::all();
        $petaSupplier = [
            1 => [1,2,9], 2 => [1,2,9], 3 => [1,2,9], 4 => [1,2,9],
            5 => [3,8], 6 => [3,8], 7 => [3,8], 8 => [3,8], 9 => [3,8], 10 => [3,8],
            11 => [4], 12 => [4], 13 => [4], 14 => [4],
            15 => [5,9], 16 => [5,9], 17 => [5,9], 18 => [5,9],
            19 => [6], 20 => [6], 21 => [6], 22 => [6],
            23 => [7], 24 => [7], 25 => [7],
            26 => [6,9], 27 => [6,9],
            28 => [3,8], 29 => [3,8], 30 => [3,8],
        ];

        foreach ($semua as $p) {
            $this->produk[$p->id] = [
                'id' => $p->id, 'nama' => $p->nama_barang, 'satuan' => $p->satuan,
                'harga_beli' => $p->harga_beli, 'harga_jual' => $p->harga_jual,
                'supplier_ids' => $petaSupplier[$p->id] ?? [1],
            ];
        }
    }

    private function loadPelanggan()
    {
        $this->individu = [
            'Budi Hartono','Maya Sari','Dodi Firmansyah','Rina Wijaya','Agus Salim',
            'Yanti Kusuma','Irwan Setiawan','Dewi Sartika','Fajar Prasetyo','Hj. Fatimah',
            'Tono Hartono','Sari Dewi','Gunawan Saputra','Bowo Prasetyo','Rara Permata',
            'Soleh Ahmad','Roni Saputra','H. Udin Firdaus','Mimin Maryati','Dika Pratama',
            'Yuni Astuti','Rudi Hermawan','Siska Melati','Adi Nugroho','Nina Rahayu',
            'Firman Hakim','Dewi Anggraini','Hasan Basri','Miftahuddin','Eko Prasetyo',
        ];

        $this->proyek = [
            ['nama' => 'Ny. Ratna - Rumah Pribadi', 'ref' => 'SPK-2026-001', 'mandor' => 'Pak Jumadi'],
            ['nama' => 'Bpk. Ahmad - Rumah Pribadi', 'ref' => 'SPK-2026-002', 'mandor' => 'Pak Sulaiman'],
            ['nama' => 'Bpk. Edi - Rumah Pribadi', 'ref' => 'SPK-2026-003', 'mandor' => 'Pak Rusdi'],
            ['nama' => 'Bpk. Herman - Ruko 2 Lantai', 'ref' => 'SPK-2026-004', 'mandor' => 'Pak Taufik'],
            ['nama' => 'Bpk. Suhartono - Rumah Baru', 'ref' => 'SPK-2026-007', 'mandor' => 'Pak Gufron'],
            ['nama' => 'Masjid Al-Falah Pontianak', 'ref' => 'SPK-2026-010', 'mandor' => 'Pak Dahlan'],
            ['nama' => 'Perumahan Green Garden Estate', 'ref' => 'SPK-2026-011', 'mandor' => 'Kontraktor Hendra'],
            ['nama' => 'Gereja Santa Maria Pontianak', 'ref' => 'SPK-2026-012', 'mandor' => 'Pak Yoseph'],
            ['nama' => 'SDN 03 Pontianak Barat', 'ref' => 'SPK-2026-013', 'mandor' => 'Dinas PU'],
            ['nama' => 'Ruko 3 Lantai - Bpk. Tan', 'ref' => 'SPK-2026-014', 'mandor' => 'Mandor Agus'],
            ['nama' => 'Cluster Melati - Perum Bumi Indah', 'ref' => 'SPK-2026-015', 'mandor' => 'Kontraktor David'],
            ['nama' => 'Bpk. RT - Rumah Dinas', 'ref' => 'SPK-2026-016', 'mandor' => 'Pak RT'],
            ['nama' => 'Kos-kosan 10 Kamar - Bu Dewi', 'ref' => 'SPK-2027-001', 'mandor' => 'Pak Saiful'],
            ['nama' => 'Renovasi Pasar Flamboyan', 'ref' => 'SPK-2027-002', 'mandor' => 'Dinas PU'],
            ['nama' => 'Pembangunan Mushola Al-Ikhlas', 'ref' => 'SPK-2027-003', 'mandor' => 'Pak Kades'],
        ];
    }

    private function loadStokAwal()
    {
        foreach (Product::all() as $p) {
            $this->stok[$p->id] = $p->stok;
        }
    }

    private function randomTgl($bulan, $tahun)
    {
        $hari = rand(1, 28);
        return sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
    }

    private function buatBM($bulan, $tahun, $urut)
    {
        // Pilih 3-6 produk yang perlu restok
        $butuhRestok = [];
        foreach ($this->produk as $id => $p) {
            $stokSekarang = $this->stok[$id] ?? 0;
            if ($stokSekarang < rand(30, 100)) {
                $butuhRestok[] = $id;
                if (count($butuhRestok) >= 8) break;
            }
        }

        // Jika semua stok cukup, tetap BM untuk produk dengan stok rendah (safety restock)
        if (count($butuhRestok) < 3) {
            $urutStok = $this->produk;
            uasort($urutStok, fn($a, $b) => ($this->stok[$a['id']] ?? 0) <=> ($this->stok[$b['id']] ?? 0));
            $butuhRestok = array_slice(array_keys($urutStok), 0, rand(3, 5));
        }

        $jmlItem = min(count($butuhRestok), rand(2, 5));
        $keys = array_rand(array_flip($butuhRestok), $jmlItem);
        $produkDipilih = is_array($keys) ? $keys : [$keys];

        // Supplier untuk item pertama menentukan supplier BM
        $supplierId = $this->produk[$produkDipilih[0]]['supplier_ids'][array_rand($this->produk[$produkDipilih[0]]['supplier_ids'])];

        $tgl = $this->randomTgl($bulan, $tahun);
        do {
            $noTrans = 'BM-' . date('Ymd', strtotime($tgl)) . '-' . rand(10000, 99999);
        } while (Transaction::where('no_transaksi', $noTrans)->exists());

        $total = 0;
        $items = [];

        foreach ($produkDipilih as $pid) {
            $p = $this->produk[$pid];
            $qty = rand(50, 200);
            $harga = $p['harga_beli'] * (1 + rand(-5, 5) / 100); // +/-5% dari harga beli standar
            $harga = max(round($harga / 100) * 100, 1000);
            $sub = $qty * $harga;
            $total += $sub;
            $items[] = [
                'product_id' => $pid,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'subtotal' => $sub,
            ];
            $this->stok[$pid] = ($this->stok[$pid] ?? 0) + $qty;
            Product::where('id', $pid)->increment('stok', $qty);
        }

        $trans = Transaction::create([
            'no_transaksi' => $noTrans,
            'jenis_transaksi' => 'masuk',
            'tanggal' => $tgl,
            'supplier_id' => $supplierId,
            'user_id' => $this->userGudang,
            'total_nilai' => $total,
            'status' => 'selesai',
        ]);

        foreach ($items as $item) {
            $item['transaction_id'] = $trans->id;
            TransactionItem::create($item);
        }

        $this->line("  BM: $noTrans (" . count($items) . " item, Rp" . number_format($total,0,',','.') . ")");
    }

    private function buatBK($bulan, $tahun, $urut)
    {
        // Tentukan apakah proyek (30%) atau individu (70%)
        $isProyek = rand(1, 100) <= 30;

        if ($isProyek) {
            $proyek = $this->proyek[array_rand($this->proyek)];
            $tujuan = $proyek['nama'];
            $kategori = 'PROYEK';
            $noRef = $proyek['ref'];
            $catatan = 'Mandor: ' . $proyek['mandor'];
            $jmlItemMax = 7;
        } else {
            $tujuan = $this->individu[array_rand($this->individu)];
            $kategori = 'PENJUALAN';
            $noRef = null;
            $catatan = null;
            $jmlItemMax = 4;
        }

        // Pilih produk yang stoknya cukup
        $produkTersedia = [];
        foreach ($this->produk as $id => $p) {
            if (($this->stok[$id] ?? 0) > 0) $produkTersedia[] = $id;
        }
        if (empty($produkTersedia)) return;

        $jmlItem = min(count($produkTersedia), rand(1, $jmlItemMax));
        if ($jmlItem < 1) return;

        $keys = array_rand(array_flip($produkTersedia), $jmlItem);
        $dipilih = is_array($keys) ? $keys : [$keys];

        $tgl = $this->randomTgl($bulan, $tahun);
        do {
            $noTrans = 'BK-' . date('Ymd', strtotime($tgl)) . '-' . rand(10000, 99999);
        } while (Transaction::where('no_transaksi', $noTrans)->exists());

        $total = 0;
        $items = [];

        foreach ($dipilih as $pid) {
            $p = $this->produk[$pid];
            $stokTersedia = $this->stok[$pid] ?? 0;
            if ($stokTersedia <= 0) continue;

            $qty = min($stokTersedia, $isProyek ? rand(2, 50) : rand(1, 8));

            // Harga: variasi natural (ada diskon, ada markup)
            $acak = rand(1, 100);
            if ($acak <= 10) {
                // 10%: rugi/dibawah modal (obral, barang basi, retur)
                $harga = round($p['harga_beli'] * rand(75, 95) / 100 / 100) * 100;
            } elseif ($acak <= 20) {
                // 10%: diskon sedang
                $harga = round($p['harga_jual'] * rand(85, 94) / 100 / 100) * 100;
            } elseif ($acak <= 90) {
                // 70%: harga normal
                $harga = $p['harga_jual'];
            } else {
                // 10%: markup (custom/urgent)
                $harga = round($p['harga_jual'] * rand(105, 115) / 100 / 100) * 100;
            }
            $harga = max($harga, 100);

            $sub = $qty * $harga;
            $total += $sub;
            $items[] = [
                'product_id' => $pid,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'subtotal' => $sub,
            ];
            $this->stok[$pid] = $stokTersedia - $qty;
            Product::where('id', $pid)->decrement('stok', $qty);
        }

        if (empty($items)) return;

        $trans = Transaction::create([
            'no_transaksi' => $noTrans,
            'jenis_transaksi' => 'keluar',
            'tanggal' => $tgl,
            'kategori_keluar' => $kategori,
            'tujuan' => $tujuan,
            'no_referensi' => $noRef,
            'catatan' => $catatan,
            'user_id' => $this->userPenjualan,
            'total_nilai' => $total,
            'status' => 'selesai',
        ]);

        foreach ($items as $item) {
            $item['transaction_id'] = $trans->id;
            TransactionItem::create($item);
        }
    }
}

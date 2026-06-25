<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateTransaksiSimulasi extends Command
{
    protected $signature = 'simulasi:fase23-generate';
    protected $description = 'Fase 2-3: Reset & generate BM+BK 2024-2026';

    private $userGudang = 4;
    private $userPenjualan = 3;
    private $produkRef = [];
    private $stok = [];
    private $totalBM = 0;
    private $totalBK = 0;

    private $individu = [
        'Budi Hartono','Maya Sari','Dodi Firmansyah','Rina Wijaya','Agus Salim',
        'Yanti Kusuma','Irwan Setiawan','Dewi Sartika','Fajar Prasetyo','Hj. Fatimah',
        'Tono Hartono','Sari Dewi','Gunawan Saputra','Bowo Prasetyo','Rara Permata',
        'Soleh Ahmad','Roni Saputra','H. Udin Firdaus','Mimin Maryati','Dika Pratama',
        'Yuni Astuti','Rudi Hermawan','Siska Melati','Adi Nugroho','Nina Rahayu',
        'Firman Hakim','Dewi Anggraini','Hasan Basri','Miftahuddin','Eko Prasetyo',
        'Agung Wijaya','Putri Maharani','Dimas Ardiansyah','Winda Lestari','Rizky Kurniawan',
        'Ratna Sari Dewi','Bayu Saputra','Fitri Handayani','Hendra Gunawan','Nia Kurniasih',
    ];

    private $proyek = [
        ['nama' => 'Masjid Al-Falah Pontianak', 'mandor' => 'Pak Dahlan'],
        ['nama' => 'Perumahan Green Garden Estate', 'mandor' => 'Kontraktor Hendra'],
        ['nama' => 'Gereja Santa Maria Pontianak', 'mandor' => 'Pak Yoseph'],
        ['nama' => 'SDN 03 Pontianak Barat', 'mandor' => 'Dinas PU'],
        ['nama' => 'Ruko 3 Lantai - Bpk. Tan', 'mandor' => 'Mandor Agus'],
        ['nama' => 'Cluster Melati Perum Bumi Indah', 'mandor' => 'Kontraktor David'],
        ['nama' => 'Kos-kosan 10 Kamar - Bu Dewi', 'mandor' => 'Pak Saiful'],
        ['nama' => 'Renovasi Pasar Flamboyan', 'mandor' => 'Dinas PU'],
        ['nama' => 'Mushola Al-Ikhlas', 'mandor' => 'Pak Kades'],
        ['nama' => 'Pembangunan Ruko Bpk. Herman', 'mandor' => 'Pak Taufik'],
        ['nama' => 'Rumah Pribadi Bpk. Ahmad', 'mandor' => 'Pak Sulaiman'],
        ['nama' => 'Rumah Pribadi Ny. Ratna', 'mandor' => 'Pak Jumadi'],
        ['nama' => 'Rumah Pribadi Bpk. Edi', 'mandor' => 'Pak Rusdi'],
        ['nama' => 'Rumah Pribadi Bpk. Suhartono', 'mandor' => 'Pak Gufron'],
        ['nama' => 'Rumah Pribadi Ny. Sumiati', 'mandor' => 'Pak Sobirin'],
        ['nama' => 'Rumah Pribadi Bpk. Yosep', 'mandor' => 'Pak Kariman'],
        ['nama' => 'Rumah Pribadi Bpk. Zainal', 'mandor' => 'Pak Ridwan'],
        ['nama' => 'Rumah Pribadi Bpk. Daryono', 'mandor' => 'Pak Marsono'],
        ['nama' => 'Pembangunan Pagar Puskesmas', 'mandor' => 'Dinas Kesehatan'],
        ['nama' => 'Renovasi Gedung Serbaguna', 'mandor' => 'Kontraktor Hendra'],
    ];

    private $season = [1=>-1,2=>-1,3=>0,4=>0,5=>1,6=>1,7=>2,8=>2,9=>1,10=>0,11=>-1,12=>-2];

    private function supBySku($sku)
    {
        if (str_starts_with($sku, 'MT') || str_starts_with($sku, 'SMN')) return [1,2,10,14];
        if (str_starts_with($sku, 'BSI')) return [3,8];
        if (str_starts_with($sku, 'KYU')) return [4];
        if (str_starts_with($sku, 'CAT')) return [5];
        if (str_starts_with($sku, 'PPA')) return [6];
        if (str_starts_with($sku, 'KMK')) return [7];
        if (str_starts_with($sku, 'PKU')) return [6];
        if (str_starts_with($sku, 'ATP')) return [3,8];
        if (str_starts_with($sku, 'ELK')) return [11];
        if (str_starts_with($sku, 'SFY')) return [12];
        if (str_starts_with($sku, 'PLN')) return [13];
        return [1];
    }

    public function handle()
    {
        $this->info('=== FASE 2: RESET DATA & GENERATE BM ===');
        $this->resetData();
        $this->loadProduk();

        $mulai = Carbon::parse('2024-01-01');
        $selesai = Carbon::parse('2026-06-01');

        for ($d = $mulai->copy(); $d->lte($selesai); $d->addMonth()) {
            $bln = $d->month;
            $thn = $d->year;
            $this->info("--- " . $d->translatedFormat('F Y') . " ---");

            DB::beginTransaction();
            try {
                $sf = $this->season[$bln] ?? 0;
                $jmlBM = rand(8, 14) + max(0, $sf);
                for ($b = 0; $b < $jmlBM; $b++) {
                    $this->buatBM($bln, $thn);
                }

                $jmlBK = rand(20, 30) + $sf * 3;
                for ($k = 0; $k < $jmlBK; $k++) {
                    $this->buatBK($bln, $thn);
                }

                DB::commit();
                $this->line("  BM: $jmlBM | BK: $jmlBK");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal $bln/$thn: " . $e->getMessage());
                return 1;
            }
        }

        $this->info("=== FASE 2-3 SELESAI! Total BM: $this->totalBM | Total BK: $this->totalBK ===");
        return 0;
    }

    private function resetData()
    {
        $this->warn('Menghapus data transaksi lama...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        TransactionItem::truncate();
        Transaction::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Product::query()->update(['stok' => 0]);
        $this->info('[OK] Semua transaksi & stok direset ke 0');
    }

    private function loadProduk()
    {
        foreach (Product::all() as $p) {
            $this->produkRef[$p->id] = [
                'id' => $p->id, 'sku' => $p->sku, 'nama' => $p->nama_barang,
                'hb' => $p->harga_beli, 'hj' => $p->harga_jual, 'satuan' => $p->satuan,
                'sup' => $this->supBySku($p->sku),
            ];
            $this->stok[$p->id] = 0;
        }
        $this->info('[OK] ' . count($this->produkRef) . ' produk dimuat');
    }

    private function randomTgl($bln, $thn)
    {
        $hari = rand(1, 28);
        return sprintf('%04d-%02d-%02d', $thn, $bln, $hari);
    }

    private function noTrans($prefix, $tgl)
    {
        static $seq = [];
        $ts = date('Ymd', strtotime($tgl));
        if (!isset($seq[$prefix][$ts])) $seq[$prefix][$ts] = 0;
        $seq[$prefix][$ts]++;
        return $prefix . '-' . $ts . '-' . str_pad($seq[$prefix][$ts], 4, '0', STR_PAD_LEFT);
    }

    private function buatBM($bln, $thn)
    {
        // Cari produk dengan stok rendah
        $urgent = [];
        foreach ($this->produkRef as $id => $p) {
            if (($this->stok[$id] ?? 0) < rand(40, 120)) {
                $urgent[] = $id;
                if (count($urgent) >= 6) break;
            }
        }
        if (count($urgent) < 2) {
            // Ambil random
            $keys = array_rand($this->produkRef, min(4, count($this->produkRef)));
            $urgent = is_array($keys) ? $keys : [$keys];
        }

        $jmlItem = min(count($urgent), rand(2, 5));
        $keys = array_rand(array_flip($urgent), $jmlItem);
        $dipilih = is_array($keys) ? $keys : [$keys];

        // Supplier dari produk pertama
        $supId = $this->produkRef[$dipilih[0]]['sup'][array_rand($this->produkRef[$dipilih[0]]['sup'])];

        $tgl = $this->randomTgl($bln, $thn);
        $no = $this->noTrans('BM', $tgl);
        $total = 0;
        $items = [];

        foreach ($dipilih as $pid) {
            $p = $this->produkRef[$pid];
            $qty = rand(50, 250);
            $harga = round($p['hb'] * (1 + rand(-5, 8) / 100) / 100) * 100;
            $harga = max($harga, 100);
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
            'no_transaksi' => $no, 'jenis_transaksi' => 'masuk', 'tanggal' => $tgl,
            'supplier_id' => $supId, 'user_id' => $this->userGudang,
            'total_nilai' => $total, 'status' => 'selesai',
        ]);

        foreach ($items as $item) {
            $item['transaction_id'] = $trans->id;
            TransactionItem::create($item);
        }

        $this->totalBM++;
    }

    private function buatBK($bln, $thn)
    {
        $isProyek = rand(1, 100) <= 28;

        if ($isProyek) {
            $pj = $this->proyek[array_rand($this->proyek)];
            $tujuan = $pj['nama'];
            $kategori = rand(1, 10) <= 8 ? 'PROYEK' : 'PENJUALAN';
            $noRef = 'SPK-' . $thn . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $catatan = 'Mandor: ' . $pj['mandor'];
            $jmlItemMax = 8;
        } else {
            $tujuan = $this->individu[array_rand($this->individu)];
            $kategori = 'PENJUALAN';
            $noRef = null;
            $catatan = null;
            $jmlItemMax = 4;
        }

        $tersedia = [];
        foreach ($this->produkRef as $id => $p) {
            if (($this->stok[$id] ?? 0) > 0) $tersedia[] = $id;
        }
        if (empty($tersedia)) return;

        $jmlItem = min(count($tersedia), rand(1, $jmlItemMax));
        if ($jmlItem < 1) return;

        $keys = array_rand(array_flip($tersedia), $jmlItem);
        $dipilih = is_array($keys) ? $keys : [$keys];

        $tgl = $this->randomTgl($bln, $thn);
        $no = $this->noTrans('BK', $tgl);
        $total = 0;
        $items = [];

        foreach ($dipilih as $pid) {
            $p = $this->produkRef[$pid];
            $stokTersedia = $this->stok[$pid] ?? 0;
            if ($stokTersedia <= 0) continue;

            $qty = min($stokTersedia, $isProyek ? rand(3, 60) : rand(1, 10));

            // Variasi harga
            $acak = rand(1, 100);
            if ($acak <= 8) {
                $harga = round($p['hb'] * rand(70, 94) / 100 / 100) * 100; // rugi
            } elseif ($acak <= 20) {
                $harga = round($p['hj'] * rand(85, 94) / 100 / 100) * 100; // diskon
            } elseif ($acak <= 92) {
                $harga = $p['hj']; // normal
            } else {
                $harga = round($p['hj'] * rand(105, 118) / 100 / 100) * 100; // markup
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
            'no_transaksi' => $no, 'jenis_transaksi' => 'keluar', 'tanggal' => $tgl,
            'kategori_keluar' => $kategori, 'tujuan' => $tujuan,
            'no_referensi' => $noRef, 'catatan' => $catatan,
            'user_id' => $this->userPenjualan,
            'total_nilai' => $total, 'status' => 'selesai',
        ]);

        foreach ($items as $item) {
            $item['transaction_id'] = $trans->id;
            TransactionItem::create($item);
        }

        $this->totalBK++;
    }
}

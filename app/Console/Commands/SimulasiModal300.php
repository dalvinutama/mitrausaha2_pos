<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SimulasiModal300 extends Command
{
    protected $signature = 'simulasi:modal-300';
    protected $description = 'Simulasi realistis modal Rp300juta mulai Jan 2023 - Jun 2026';

    private $userGudang = 4;
    private $userPenjualan = 3;
    private $produkRef = [];
    private $stok = [];
    private $totalBM = 0;
    private $totalBK = 0;
    private $totalNilaiBK = 0;
    private $totalNilaiBM = 0;
    private $totalItemBK = 0;
    private $totalItemBM = 0;

    private $individu = [
        'Pak Herman','Bu Sari','Bang Rudi','Mbak Dewi','Pak Agus','Bu Yanti',
        'Mas Bowo','Mbak Rina','Pak Dodi','Bu Maya','Bang Irwan','Mbak Siska',
        'Pak Tono','Bu Dewi','Mas Fajar','Mbak Yuni','Pak Gunawan','Bu Nina',
        'Bang Adi','Mbak Winda','Pak Firman','Bu Ratna','Mas Dimas','Mbak Fitri',
        'Pak Hasan','Bu Nia','Bang Eko','Mbak Lia','Pak Roni','Bu Rara',
        'Mas Reza','Mbak Indah','Pak Agung','Bu Sri','Bang Wisnu','Mbak Lisa',
        'Pak Saiful','Bu Mimin','Mas Heru','Mbak Nita','Pak Yanto','Bu Tini',
        'Bang Deni','Mbak Kiki','Pak Udin','Bu Santi','Mas Ilham','Mbak Tia',
        'Pak Edi','Bu Pariyem','Bang Yuda','Mbak Sari',
    ];

    private $proyek = [
        ['nama' => 'Rumah Pribadi - Bpk. Zainal', 'mandor' => 'Pak Ridwan'],
        ['nama' => 'Rumah Pribadi - Ibu Sumiati', 'mandor' => 'Pak Sobirin'],
        ['nama' => 'Rumah Pribadi - Bpk. Daryono', 'mandor' => 'Pak Marsono'],
        ['nama' => 'Renovasi Dapur - Ibu Dewi', 'mandor' => 'Pak Saiful'],
        ['nama' => 'Kamar Mandi Baru - Bpk. Yosep', 'mandor' => 'Pak Kariman'],
        ['nama' => 'Pagar Rumah - Bpk. Hartono', 'mandor' => 'Pak Jumadi'],
        ['nama' => 'Teras Rumah - Bpk. Sulaiman', 'mandor' => 'Pak Taufik'],
        ['nama' => 'Mushola Al-Ikhlas', 'mandor' => 'Pak Kades'],
        ['nama' => 'Renovasi Toko - Bpk. Edi', 'mandor' => 'Pak Gufron'],
        ['nama' => 'Pagar Puskesmas', 'mandor' => 'Dinas Kesehatan'],
        ['nama' => 'Taman Kota', 'mandor' => 'Dinas PU'],
        ['nama' => 'Kandang Ayam - Pak Jamil', 'mandor' => 'Pak Jamil'],
        ['nama' => 'Ruko 2 Lantai - Bpk. Tan', 'mandor' => 'Mandor Agus'],
    ];

    private function growthFactor($thn, $bln)
    {
        $tahunPenuh = ($thn - 2023) + ($bln - 1) / 12;
        if ($tahunPenuh < 1) return 1.0 + $tahunPenuh * 0.18;
        if ($tahunPenuh < 2) return 1.18 + ($tahunPenuh - 1) * 0.15;
        if ($tahunPenuh < 3) return 1.33 + ($tahunPenuh - 2) * 0.12;
        return 1.45 + ($tahunPenuh - 3) * 0.10;
    }

    private function seasonFactor($bln)
    {
        if (in_array($bln, [6,7,8,9,10])) return 0.30;
        if (in_array($bln, [11,12,1,2])) return -0.15;
        return 0;
    }

    private function bkCount($growth, $season)
    {
        $base = rand(65, 110);
        $adj = round($base * ($growth + $season));
        return max(40, min(130, $adj));
    }

    public function handle()
    {
        $this->info('=== SIMULASI MODAL AWAL Rp300.000.000 ===');
        $this->newLine();
        $this->resetData();
        $this->loadProduk();

        $investasiAwal = $this->alokasiInitialStock();
        $this->line("  Investasi stok awal: Rp " . number_format($investasiAwal, 0, ',', '.'));
        $this->newLine();

        $mulai = Carbon::parse('2023-01-01');
        $selesai = Carbon::parse('2026-06-01');
        $bulanKe = 0;

        for ($d = $mulai->copy(); $d->lte($selesai); $d->addMonth()) {
            $bln = $d->month;
            $thn = $d->year;
            $bulanKe++;
            $growth = $this->growthFactor($thn, $bln);
            $season = $this->seasonFactor($bln);

            DB::beginTransaction();
            try {
                $jmlBK = $this->bkCount($growth, $season);
                for ($k = 0; $k < $jmlBK; $k++) {
                    $this->buatBK($bln, $thn);
                }
                $jmlBM = $this->buatBMReplenish($bln, $thn);
                DB::commit();

                if ($bulanKe % 6 == 0) {
                    $stokTotal = array_sum($this->stok);
                    $this->line("  $bulanKe. " . $d->translatedFormat('F Y') . " -> BM: {$this->totalBM} | BK: {$this->totalBK} | Stok: " . number_format($stokTotal, 0, ',', '.') . " unit");
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal $bln/$thn: " . $e->getMessage());
                return 1;
            }
        }

        $this->newLine();
        $this->info('=== HASIL AKHIR ===');
        $this->line("Total BM: $this->totalBM (" . number_format($this->totalItemBM, 0, ',', '.') . " item)");
        $this->line("Total BK: $this->totalBK (" . number_format($this->totalItemBK, 0, ',', '.') . " item)");
        $this->line("Nilai BK: Rp " . number_format($this->totalNilaiBK, 0, ',', '.'));
        $this->line("Nilai BM: Rp " . number_format($this->totalNilaiBM, 0, ',', '.'));
        $this->line("Laba kotor: Rp " . number_format($this->totalNilaiBK - $this->totalNilaiBM, 0, ',', '.'));

        $stokAkhir = array_sum($this->stok);
        $nilaiStokAkhir = 0;
        foreach ($this->produkRef as $pid => $p) {
            $nilaiStokAkhir += ($this->stok[$pid] ?? 0) * $p['hb'];
        }
        $this->line("Stok akhir: " . number_format($stokAkhir, 0, ',', '.') . " unit (Rp " . number_format($nilaiStokAkhir, 0, ',', '.') . ")");
        $this->info('=== SELESAI ===');
        return 0;
    }

    private function resetData()
    {
        $this->warn('Reset data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        TransactionItem::truncate();
        Transaction::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Product::query()->update(['stok' => 0]);
        $this->info('[OK] Reset selesai');
    }

    private function loadProduk()
    {
        foreach (Product::all() as $p) {
            $this->produkRef[$p->id] = [
                'id' => $p->id, 'sku' => $p->sku, 'nama' => $p->nama_barang,
                'kat_id' => $p->kategori_id,
                'hb' => $p->harga_beli, 'hj' => $p->harga_jual, 'satuan' => $p->satuan,
                'sup' => $this->supBySku($p->sku),
            ];
            $this->stok[$p->id] = 0;
        }
        $this->info(count($this->produkRef) . ' produk dimuat');
    }

    private function alokasiInitialStock()
    {
        $totalInvest = 0;
        foreach ($this->produkRef as $pid => $p) {
            if ($p['hb'] <= 15000) {
                $qty = rand(150, 350);
            } elseif ($p['hb'] <= 50000) {
                $qty = rand(60, 160);
            } elseif ($p['hb'] <= 100000) {
                $qty = rand(30, 80);
            } elseif ($p['hb'] <= 200000) {
                $qty = rand(12, 35);
            } else {
                $qty = rand(5, 18);
            }
            $this->stok[$pid] = $qty;
            Product::where('id', $pid)->update(['stok' => $qty]);
            $totalInvest += $qty * $p['hb'];
        }
        return $totalInvest;
    }

    private function randomTgl($bln, $thn)
    {
        return sprintf('%04d-%02d-%02d', $thn, $bln, rand(1, 28));
    }

    private function noTrans($prefix, $tgl)
    {
        static $seq = [];
        $ts = date('Ymd', strtotime($tgl));
        if (!isset($seq[$prefix][$ts])) $seq[$prefix][$ts] = 0;
        $seq[$prefix][$ts]++;
        return $prefix . '-' . $ts . '-' . str_pad($seq[$prefix][$ts], 4, '0', STR_PAD_LEFT);
    }

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

    private function pilihItemBK($jml, $isProyek)
    {
        $tersedia = [];
        foreach ($this->produkRef as $id => $p) {
            if (($this->stok[$id] ?? 0) > 0) $tersedia[] = $id;
        }
        if (empty($tersedia)) return [];

        $jmlItem = min(count($tersedia), $jml);
        if ($jmlItem < 1) return [];

        $keys = array_rand(array_flip($tersedia), $jmlItem);
        $dipilih = is_array($keys) ? $keys : [$keys];

        // Ambil beberapa item yang sering dibeli bareng (bahan bangunan yg saling melengkapi)
        $items = [];
        foreach ($dipilih as $pid) {
            $p = $this->produkRef[$pid];
            $stokTersedia = $this->stok[$pid] ?? 0;
            if ($stokTersedia <= 0) continue;

            // Distribusi natural: 70% beli 1-2, 25% beli 3-4, 5% beli 5+
            $r = rand(1, 100);
            if ($r <= 40) {
                $qty = 1;
            } elseif ($r <= 70) {
                $qty = 2;
            } elseif ($r <= 88) {
                $qty = $isProyek ? rand(3, 5) : 3;
            } elseif ($r <= 97) {
                $qty = $isProyek ? rand(3, 6) : rand(3, 4);
            } else {
                $qty = $isProyek ? rand(4, 8) : rand(4, 6);
            }
            $qty = min($qty, $stokTersedia);
            if ($qty <= 0) continue;

            $acak = rand(1, 100);
            if ($acak <= 5) {
                $harga = round($p['hb'] * rand(85, 94) / 100 / 100) * 100;
            } elseif ($acak <= 17) {
                $harga = round($p['hj'] * rand(85, 94) / 100 / 100) * 100;
            } elseif ($acak <= 92) {
                $harga = $p['hj'];
            } else {
                $harga = round($p['hj'] * rand(105, 120) / 100 / 100) * 100;
            }
            $harga = max($harga, 100);

            $items[] = [
                'product_id' => $pid,
                'qty' => $qty,
                'harga_satuan' => $harga,
                'subtotal' => $qty * $harga,
            ];
            $this->stok[$pid] = $stokTersedia - $qty;
            Product::where('id', $pid)->decrement('stok', $qty);
        }
        return $items;
    }

    private function buatBK($bln, $thn)
    {
        $isProyek = rand(1, 100) <= 30;

        if ($isProyek) {
            $pj = $this->proyek[array_rand($this->proyek)];
            $tujuan = $pj['nama'];
            $kategori = 'PROYEK';
            $noRef = 'SPK-' . $thn . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $catatan = 'Mandor: ' . $pj['mandor'];
            $jmlItem = rand(3, 6);
        } else {
            $tujuan = $this->individu[array_rand($this->individu)];
            $kategori = 'PENJUALAN';
            $noRef = null;
            $catatan = null;
            $jmlItem = rand(2, 5);
        }

        $items = $this->pilihItemBK($jmlItem, $isProyek);
        if (empty($items)) return;

        $tgl = $this->randomTgl($bln, $thn);
        $total = array_sum(array_column($items, 'subtotal'));

        $trans = Transaction::create([
            'no_transaksi' => $this->noTrans('BK', $tgl),
            'jenis_transaksi' => 'keluar', 'tanggal' => $tgl,
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
        $this->totalNilaiBK += $total;
        $this->totalItemBK += count($items);
    }

    private function buatBMReplenish($bln, $thn)
    {
        // Cari produk yang stoknya menipis, kelompokkan per supplier
        $supProduk = [];
        foreach ($this->produkRef as $id => $p) {
            $sisa = $this->stok[$id] ?? 0;
            $threshold = $p['hb'] <= 15000 ? 25 : (
                $p['hb'] <= 50000 ? 12 : (
                $p['hb'] <= 100000 ? 8 : (
                $p['hb'] <= 200000 ? 4 : 2
            )));
            if ($sisa < $threshold) {
                foreach ($p['sup'] as $supId) {
                    $supProduk[$supId][] = $id;
                }
            }
        }

        if (empty($supProduk)) return 0;

        $bmCount = 0;
        foreach ($supProduk as $supId => $productIds) {
            $productIds = array_unique($productIds);
            shuffle($productIds);

            // Maks 5 item per BM
            $ambil = array_slice($productIds, 0, min(5, count($productIds)));

            $tgl = $this->randomTgl($bln, $thn);
            $total = 0;
            $items = [];

            foreach ($ambil as $pid) {
                $p = $this->produkRef[$pid];
                $sisa = $this->stok[$pid] ?? 0;

                if ($p['hb'] <= 15000) {
                    $target = rand(40, 80);
                } elseif ($p['hb'] <= 50000) {
                    $target = rand(20, 50);
                } elseif ($p['hb'] <= 100000) {
                    $target = rand(10, 30);
                } elseif ($p['hb'] <= 200000) {
                    $target = rand(5, 15);
                } else {
                    $target = rand(3, 10);
                }
                $qty = max(1, $target - $sisa);

                $harga = round($p['hb'] * (1 + rand(-3, 5) / 100) / 100) * 100;
                $harga = max($harga, 100);
                $sub = $qty * $harga;
                $total += $sub;

                $items[] = [
                    'product_id' => $pid,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'subtotal' => $sub,
                ];
                $this->stok[$pid] = $sisa + $qty;
                Product::where('id', $pid)->increment('stok', $qty);
            }

            if (empty($items)) continue;

            $trans = Transaction::create([
                'no_transaksi' => $this->noTrans('BM', $tgl),
                'jenis_transaksi' => 'masuk', 'tanggal' => $tgl,
                'supplier_id' => $supId, 'user_id' => $this->userGudang,
                'total_nilai' => $total, 'status' => 'selesai',
            ]);

            foreach ($items as $item) {
                $item['transaction_id'] = $trans->id;
                TransactionItem::create($item);
            }

            $this->totalBM++;
            $this->totalNilaiBM += $total;
            $this->totalItemBM += count($items);
            $bmCount++;
        }

        return $bmCount;
    }
}

<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\StockOpname;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardService
{
    public function getAset(): array
    {
        $detailAset = Product::where('stok', '>', 0)->get();
        $totalAset = $detailAset->sum(fn($p) => $p->stok * $p->harga_beli);
        return [$totalAset, $detailAset];
    }

    public function getBarangRusak(Carbon $startDate, Carbon $endDate): array
    {
        $detailRusak = Transaction::with(['items.product', 'supplier'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('jenis_transaksi', 'keluar')
            ->where('catatan', 'NOT LIKE', '%[RETUR SELESAI]%')
            ->where(fn($q) => $q
                ->where('kategori_keluar', 'LIKE', '%rusak%')
                ->orWhere('kategori_keluar', 'LIKE', '%retur%')
                ->orWhere('catatan', 'LIKE', '%rusak%')
                ->orWhere('catatan', 'LIKE', '%retur%'))
            ->get();

        $barangRusak = $detailRusak->sum(fn($trx) => $trx->items ? $trx->items->sum('qty') : 0);
        return [$barangRusak, $detailRusak];
    }

    public function getPoAktif(Carbon $startDate, Carbon $endDate): array
    {
        $queryPO = Transaction::whereIn('jenis_transaksi', ['po', 'purchase_order', 'PO', 'Purchase Order', 'pesanan'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if (Schema::hasColumn('transactions', 'status')) {
            $queryPO->whereNotIn('status', ['selesai', 'lunas', 'Selesai', 'Lunas', 'batal', 'Batal']);
        }

        $detailPO = $queryPO->get();
        return [$detailPO->count(), $detailPO];
    }

    public function getHutang(): array
    {
        $hutangTempo = 0;
        $detailHutang = collect([]);

        if (Schema::hasColumn('transactions', 'status_pembayaran')) {
            $detailHutang = Transaction::with(['supplier', 'payments'])
                ->whereIn('status_pembayaran', ['belum lunas', 'Belum Lunas', 'hutang', 'Hutang', 'belum_lunas'])
                ->get();

            foreach ($detailHutang as $h) {
                $sudahBayar = $h->payments ? $h->payments->sum('nominal') : 0;
                $h->sisa_hutang = $h->total_nilai - $sudahBayar;
                $hutangTempo += $h->sisa_hutang;
                $h->tanggal_tempo = $h->tanggal;
                if (preg_match('/Jatuh Tempo:\s*([\d\/]+)/i', $h->catatan, $m)) {
                    try { $h->tanggal_tempo = Carbon::createFromFormat('d/m/Y', trim($m[1]))->format('Y-m-d'); } catch (\Exception $e) {}
                }
                $h->keterangan = 'Hutang Faktur Masuk';
            }
        }
        return [$hutangTempo, $detailHutang];
    }

    public function getChartData(): array
    {
        $sumQty = fn($jenis, $dateValue, $yearValue = null) => (int) DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.jenis_transaksi', $jenis)
            ->when($yearValue, fn($q) => $q->whereMonth('transactions.tanggal', $dateValue)->whereYear('transactions.tanggal', $yearValue))
            ->when(!$yearValue && strlen((string)$dateValue) == 4, fn($q) => $q->whereYear('transactions.tanggal', $dateValue))
            ->when(!$yearValue && strlen((string)$dateValue) != 4, fn($q) => $q->whereDate('transactions.tanggal', $dateValue))
            ->sum('transaction_items.qty');

        $data = ['week' => [], 'month' => [], 'quarter' => [], '6months' => [], 'year' => [], '5years' => [], 'all' => []];
        $now = Carbon::now();

        // Week
        $startWeek = $now->copy()->startOfWeek(Carbon::SUNDAY);
        for ($i = 0; $i < 7; $i++) {
            $d = $startWeek->copy()->addDays($i);
            $data['week']['labels'][] = $d->translatedFormat('D');
            $data['week']['masuk'][] = $sumQty('masuk', $d->format('Y-m-d'));
            $data['week']['keluar'][] = $sumQty('keluar', $d->format('Y-m-d'));
        }

        // Month
        $startMonth = $now->copy()->startOfMonth();
        for ($i = 0; $i < $now->daysInMonth; $i++) {
            $d = $startMonth->copy()->addDays($i);
            $data['month']['labels'][] = $d->format('d');
            $data['month']['masuk'][] = $sumQty('masuk', $d->format('Y-m-d'));
            $data['month']['keluar'][] = $sumQty('keluar', $d->format('Y-m-d'));
        }

        // Year (monthly)
        for ($i = 1; $i <= 12; $i++) {
            $d = Carbon::create($now->year, $i, 1);
            $data['year']['labels'][] = $d->translatedFormat('M');
            $data['year']['masuk'][] = $sumQty('masuk', $i, $now->year);
            $data['year']['keluar'][] = $sumQty('keluar', $i, $now->year);
        }

        // Quarter
        for ($i = 2; $i >= 0; $i--) {
            $d = $now->copy()->startOfMonth()->subMonths($i);
            $data['quarter']['labels'][] = $d->translatedFormat('M y');
            $data['quarter']['masuk'][] = $sumQty('masuk', $d->month, $d->year);
            $data['quarter']['keluar'][] = $sumQty('keluar', $d->month, $d->year);
        }

        // 6 months
        for ($i = 5; $i >= 0; $i--) {
            $d = $now->copy()->startOfMonth()->subMonths($i);
            $data['6months']['labels'][] = $d->translatedFormat('M y');
            $data['6months']['masuk'][] = $sumQty('masuk', $d->month, $d->year);
            $data['6months']['keluar'][] = $sumQty('keluar', $d->month, $d->year);
        }

        // 5 years
        $maxYear = (int) Transaction::max(DB::raw('YEAR(tanggal)')) ?: $now->year;
        $chartEndYear = max($now->year, $maxYear);
        for ($i = $chartEndYear - 4; $i <= $chartEndYear; $i++) {
            $data['5years']['labels'][] = (string) $i;
            $data['5years']['masuk'][] = $sumQty('masuk', $i);
            $data['5years']['keluar'][] = $sumQty('keluar', $i);
        }

        // All time
        $firstTrx = Transaction::orderBy('tanggal', 'asc')->first();
        $startYear = $firstTrx ? Carbon::parse($firstTrx->tanggal)->year : $chartEndYear;
        for ($i = $startYear; $i <= $chartEndYear; $i++) {
            $data['all']['labels'][] = (string) $i;
            $data['all']['masuk'][] = $sumQty('masuk', $i);
            $data['all']['keluar'][] = $sumQty('keluar', $i);
        }

        return [$data, $data['week'], $data['month']];
    }

    public function getKategoriTerlaris(Carbon $startDate, Carbon $endDate): \Illuminate\Support\Collection
    {
        $kategoriTerlaris = collect([]);
        if (!Schema::hasTable('transaction_items')) return $kategoriTerlaris;

        $kategoriRaw = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.tanggal', [$startDate, $endDate])
            ->where('transactions.jenis_transaksi', 'keluar')
            ->whereNotNull('transactions.kategori_keluar')
            ->where('transactions.kategori_keluar', '!=', '')
            ->select('transactions.kategori_keluar', DB::raw('SUM(transaction_items.qty) as total_qty_keluar'))
            ->groupBy('transactions.kategori_keluar')
            ->orderBy('total_qty_keluar', 'desc')
            ->take(4)
            ->get();

        foreach ($kategoriRaw as $kat) {
            $qtyRusak = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->whereBetween('transactions.tanggal', [$startDate, $endDate])
                ->where('transactions.jenis_transaksi', 'keluar')
                ->where('transactions.kategori_keluar', $kat->kategori_keluar)
                ->where(fn($q) => $q->where('transactions.kategori_keluar', 'LIKE', '%rusak%')->orWhere('transactions.catatan', 'LIKE', '%rusak%'))
                ->sum('transaction_items.qty');

            $kategoriTerlaris->push((object)[
                'nama_kategori' => $kat->kategori_keluar,
                'kategori_keluar' => $kat->kategori_keluar,
                'total_qty_keluar' => $kat->total_qty_keluar,
                'total_qty_rusak' => (int) $qtyRusak,
                'items' => collect([]),
            ]);
        }

        return $kategoriTerlaris;
    }
}

<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function generate(int $range): array
    {
        $daysAgo = Carbon::now()->subDays($range);
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $totalAset = Product::sum(DB::raw('stok * harga_beli')) ?: 1;
        $hutangTempo = Transaction::where('jenis_transaksi', 'po')
            ->where('tipe_pembayaran', 'credit')
            ->whereNotIn('status', ['lunas', 'Lunas', 'LUNAS'])
            ->sum('total_nilai');

        $revenueTahunan = Transaction::where('jenis_transaksi', 'keluar')
            ->where('tanggal', '>=', Carbon::now()->subYear())->sum('total_nilai');
        $totalBiayaBeli = Transaction::where('jenis_transaksi', 'masuk')
            ->where('tanggal', '>=', Carbon::now()->subYear())->sum('total_nilai');

        $keluar = Transaction::where('jenis_transaksi', 'keluar')
            ->where('tanggal', '>=', $sixMonthsAgo)->with('items')->get();
        $masuk = Transaction::where('jenis_transaksi', 'masuk')
            ->where('tanggal', '>=', $sixMonthsAgo)->with('items')->get();
        $totalKeluarQty = $keluar->flatMap->items->sum('qty');
        $totalMasukQty = $masuk->flatMap->items->sum('qty');

        $dailyOutflow = Transaction::where('jenis_transaksi', 'keluar')
            ->where('tanggal', '>=', $daysAgo)
            ->select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(total_nilai) as total'))
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $dailyQtyOutflow = Transaction::join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->where('transactions.jenis_transaksi', 'keluar')
            ->where('transactions.tanggal', '>=', $daysAgo)
            ->select(DB::raw('DATE(transactions.tanggal) as date'), DB::raw('SUM(transaction_items.qty) as total'))
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $dailyDemandArr = [];
        for ($i = $range; $i >= 0; $i--) {
            $dateStr = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyDemandArr[] = $dailyQtyOutflow[$dateStr] ?? 0;
        }

        $avgDailyDemand = count($dailyDemandArr) > 0 ? array_sum($dailyDemandArr) / count($dailyDemandArr) : 0;
        $varianceDemand = 0;
        if (count($dailyDemandArr) > 1) {
            $sqDiffs = array_map(fn($x) => pow($x - $avgDailyDemand, 2), $dailyDemandArr);
            $varianceDemand = array_sum($sqDiffs) / (count($dailyDemandArr) - 1);
        }
        $stdDevDemand = sqrt($varianceDemand);

        $pos = Transaction::where('jenis_transaksi', 'po')
            ->whereNotNull('estimasi_datang')
            ->where('tanggal', '>=', $sixMonthsAgo)->get();
        $totalLeadTimeDays = 0; $poCount = 0;
        foreach ($pos as $po) {
            $diff = Carbon::parse($po->tanggal)->diffInDays(Carbon::parse($po->estimasi_datang));
            if ($diff >= 0) { $totalLeadTimeDays += $diff; $poCount++; }
        }
        $leadTime = max(1, $poCount > 0 ? ($totalLeadTimeDays / $poCount) : 3);

        $totalStok = Product::sum('stok');
        $inventoryTurnover = $totalStok > 0 ? ($totalKeluarQty / $totalStok) : 0;
        $daysInventoryOutstanding = $inventoryTurnover > 0 ? (180 / $inventoryTurnover) : 999;
        $capitalReturnRatio = $totalAset > 0 ? ($revenueTahunan / $totalAset) : 0;

        $solvabilitas = $hutangTempo > 0
            ? ($totalAset / $hutangTempo) * max(0.01, $inventoryTurnover)
            : $capitalReturnRatio * 10;
        $solvabilitasStat = $solvabilitas > 1.5 ? "Aman" : "Kritis";

        $inventoryEfficiency = $daysInventoryOutstanding <= 90 ? 100 : ($daysInventoryOutstanding <= 180 ? 70 : ($daysInventoryOutstanding <= 365 ? 40 : max(5, 100 - ($daysInventoryOutstanding / 10))));
        $capitalEfficiency = $capitalReturnRatio >= 1.5 ? 100 : ($capitalReturnRatio >= 0.8 ? 70 : ($capitalReturnRatio >= 0.3 ? 40 : max(5, $capitalReturnRatio * 100)));

        $zScore95 = 1.65;
        $rop = ($avgDailyDemand * $leadTime) + ($zScore95 * sqrt($leadTime * $varianceDemand));

        $dioDampener = $daysInventoryOutstanding <= 90 ? 1.0 : ($daysInventoryOutstanding <= 180 ? 0.8 : ($daysInventoryOutstanding <= 365 ? 0.5 : max(0.3, 365 / $daysInventoryOutstanding)));

        $n = count($dailyDemandArr);
        $sumX = 0; $sumY = 0; $sumXY = 0; $sumX2 = 0;
        for ($i = 0; $i < $n; $i++) {
            $x = $i + 1; $y = $dailyDemandArr[$i];
            $sumX += $x; $sumY += $y; $sumXY += ($x * $y); $sumX2 += ($x * $x);
        }
        $denominator = ($n * $sumX2) - ($sumX * $sumX);
        $slope = $denominator > 0 ? (($n * $sumXY) - ($sumX * $sumY)) / $denominator : 0;
        $intercept = $n > 0 ? ($sumY - ($slope * $sumX)) / $n : 0;

        $forecastRawHW = 0;
        for ($i = 1; $i <= 30; $i++) $forecastRawHW += max(0, ($slope * ($n + $i)) + $intercept);
        $forecastHW = $forecastRawHW * $dioDampener;

        $p_aman_kritis = $solvabilitas < 1.5 ? 0.40 : 0.10;

        $momentum = ($n > 15) ? (array_sum(array_slice($dailyDemandArr, -15)) / 15) : $avgDailyDemand;
        $sarima = ($momentum * 30 * 1.05) * $dioDampener;

        $stockoutScenarios = 0;
        for ($sim = 0; $sim < 100; $sim++) {
            $simulatedDemand = 0;
            for ($day = 0; $day < 30; $day++) {
                $u1 = max(0.0001, mt_rand() / mt_getrandmax());
                $u2 = mt_rand() / mt_getrandmax();
                $z0 = sqrt(-2.0 * log($u1)) * cos(2.0 * M_PI * $u2);
                $simulatedDemand += max(0, $avgDailyDemand + ($z0 * $stdDevDemand));
            }
            if ($simulatedDemand > $totalStok) $stockoutScenarios++;
        }
        $peluangHabis = ($stockoutScenarios / 100) * 100;

        $modalKerja = $totalAset - $hutangTempo;
        $totalSalesRevenue = Transaction::where('jenis_transaksi', 'keluar')
            ->where('tanggal', '>=', Carbon::now()->subYear())->sum('total_nilai');

        $x1 = $modalKerja / $totalAset;
        $x2 = ($totalSalesRevenue * 0.20) / $totalAset;
        $x3 = ($totalSalesRevenue * 0.15) / $totalAset;
        $x4 = $hutangTempo > 0 ? ($totalAset - $hutangTempo) / $hutangTempo : 100;
        $zScoreAltman = (6.56 * $x1) + (3.26 * $x2) + (6.72 * $x3) + (1.05 * $x4);

        $biayaPesan = config('bisnis.ordering_cost');
        $annualDemand = $totalKeluarQty * 2;
        $avgItemValue = Product::avg('harga_beli') ?: 10000;
        $biayaSimpan = max(500, $avgItemValue * 0.05);
        $eoq = sqrt((2 * $annualDemand * $biayaPesan) / $biayaSimpan);

        $korelasi = $slope > 0.1 ? "Positif Kuat" : ($slope < -0.1 ? "Negatif Kuat" : "Stabil (Stagnan)");

        $activeProductIds = TransactionItem::whereHas('transaction', fn($q) => $q->where('jenis_transaksi', 'keluar')->where('tanggal', '>=', $sixMonthsAgo))
            ->pluck('product_id')->unique()->toArray();
        $totalProducts = Product::count();
        $deadStockCount = Product::whereNotIn('id', $activeProductIds)->count();
        $weibullRisk = $totalProducts > 0 ? ($deadStockCount / $totalProducts) * 100 : 0;

        $latePosCount = Transaction::where('jenis_transaksi', 'po')
            ->where('status', 'selesai')
            ->where('updated_at', '>', DB::raw('estimasi_datang'))->count();
        $totalPosCount = Transaction::where('jenis_transaksi', 'po')->count();
        $bayesianScore = $totalPosCount > 0 ? max(0, 100 - (($latePosCount / $totalPosCount) * 100)) : 100;

        $velocity = $totalKeluarQty - $totalMasukQty;

        $bulanStokMenumpuk = round($daysInventoryOutstanding / 30);
        $dioText = $bulanStokMenumpuk . " bulan";
        $rpAsetTerjual = "Rp " . number_format($revenueTahunan, 0, ',', '.');

        if ($zScoreAltman < 1.8) {
            $kesimpulan_id = "Z-Score menunjukkan potensi kesulitan likuiditas. Tahan pesanan baru dan percepat penagihan.";
            $kesimpulan_en = "Z-Score indicates potential liquidity issues. Hold new orders and accelerate collections.";
            $status = "error";
        } elseif ($daysInventoryOutstanding > 365) {
            $kesimpulan_id = "Stok menumpuk lebih dari 12 bulan! Hentikan pembelian barang baru. Fokus obral besar-besaran untuk cairkan modal.";
            $kesimpulan_en = "Stock piled for over 12 months! Stop new purchases. Focus on massive clearance sales to free up capital.";
            $status = "error";
        } elseif ($peluangHabis > 50) {
            $kesimpulan_id = "Monte Carlo memprediksi peluang Stockout tinggi ($peluangHabis%). Namun perhatikan juga DIO ($dioText) - jangan sampai overstock di produk lain.";
            $kesimpulan_en = "Monte Carlo predicts high Stockout probability ($peluangHabis%). Also watch DIO ($dioText) - don't overstock other items.";
            $status = "warning";
        } elseif ($revenueTahunan < $totalBiayaBeli * 0.5) {
            $kesimpulan_id = "Peringatan: Pembelian barang (Rp " . number_format($totalBiayaBeli, 0, ',', '.') . ") jauh melebihi penjualan ($rpAsetTerjual). Modal tersedot ke stok! Segera evaluasi.";
            $kesimpulan_en = "Warning: Purchases (Rp " . number_format($totalBiayaBeli, 0, ',', '.') . ") far exceed sales ($rpAsetTerjual). Capital trapped in stock!";
            $status = "warning";
        } else {
            $kesimpulan_id = "Sistem dalam kondisi sehat. Perputaran stok $dioText, penjualan seimbang dengan pembelian. Pertahankan!";
            $kesimpulan_en = "System is healthy. Stock turnover $dioText, sales balanced with purchases. Keep it up!";
            $status = "success";
        }

        $chartLabels = []; $historicalData = []; $futureData = [];
        for ($i = $range; $i >= 0; $i--) {
            $dateStr = Carbon::now()->subDays($i)->format('Y-m-d');
            $val = ($dailyOutflow[$dateStr] ?? 0) / 1000;
            $chartLabels[] = Carbon::now()->subDays($i)->format('d M');
            $historicalData[] = round($val);
            $futureData[] = null;
        }

        $valArr = [];
        for ($i = $range; $i >= 0; $i--) {
            $valArr[] = (isset($dailyOutflow[Carbon::now()->subDays($i)->format('Y-m-d')]) ? $dailyOutflow[Carbon::now()->subDays($i)->format('Y-m-d')] : 0) / 1000;
        }
        $nVal = count($valArr);
        $sX = 0; $sY = 0; $sXY = 0; $sX2 = 0;
        for ($i = 0; $i < $nVal; $i++) {
            $x = $i + 1; $y = $valArr[$i];
            $sX += $x; $sY += $y; $sXY += ($x * $y); $sX2 += ($x * $x);
        }
        $denomVal = ($nVal * $sX2) - ($sX * $sX);
        $slopeVal = $denomVal > 0 ? (($nVal * $sXY) - ($sX * $sY)) / $denomVal : 0;
        $interceptVal = $nVal > 0 ? ($sY - ($slopeVal * $sX)) / $nVal : 0;

        $futureData[count($futureData) - 1] = $historicalData[count($historicalData) - 1];
        for ($i = 1; $i <= $range; $i++) {
            $chartLabels[] = Carbon::now()->addDays($i)->format('d M');
            $historicalData[] = null;
            $futureData[] = round(max(0, ($slopeVal * ($nVal + $i)) + $interceptVal));
        }

        $rpAset = "Rp " . number_format($totalAset, 0, ',', '.');
        $rpHutang = "Rp " . number_format($hutangTempo, 0, ',', '.');
        $qtyTerjual = number_format($totalKeluarQty, 0, ',', '.');
        $stokAvg = number_format(max(1, Product::sum('stok')), 0, ',', '.');
        $rpModalKerja = "Rp " . number_format($modalKerja, 0, ',', '.');

        $scoreSolvabilitas = min(25, max(0, ($solvabilitas / 2.0) * 25));
        $scoreZ = min(15, max(0, (($zScoreAltman - 1.0) / 3.0) * 15));
        $scoreMC = 10 - min(10, max(0, ($peluangHabis / 100) * 10));
        $scoreInvEfisiensi = min(30, max(0, ($inventoryEfficiency / 100) * 30));
        $scoreModal = min(20, max(0, ($capitalEfficiency / 100) * 20));
        $overallScore = min(100, max(0, round($scoreSolvabilitas + $scoreZ + $scoreMC + $scoreInvEfisiensi + $scoreModal)));

        if ($overallScore >= 80) {
            $execId = "Toko Anda dalam kondisi <b>Sangat Prima</b>.";
            $execEn = "Your store is in <b>Excellent</b> condition.";
            $execColor = "emerald";
        } elseif ($overallScore >= 60) {
            $execId = "Toko Anda <b>Cukup Stabil</b>.";
            $execEn = "Your store is <b>Quite Stable</b>.";
            $execColor = "indigo";
        } elseif ($overallScore >= 40) {
            $execId = "Toko Anda <b>Perlu Perhatian</b>.";
            $execEn = "Your store needs <b>Attention</b>.";
            $execColor = "indigo";
        } else {
            $execId = "Toko Anda dalam kondisi <b>KRITIS</b>.";
            $execEn = "Your store is <b>CRITICAL</b>.";
            $execColor = "orange";
        }

        return compact(
            'solvabilitas', 'solvabilitasStat', 'rop', 'forecastHW', 'p_aman_kritis',
            'sarima', 'peluangHabis', 'zScoreAltman', 'eoq', 'korelasi', 'weibullRisk',
            'bayesianScore', 'velocity', 'daysInventoryOutstanding', 'dioText',
            'dioDampener', 'capitalReturnRatio', 'kesimpulan_id', 'kesimpulan_en',
            'status', 'overallScore', 'execId', 'execEn', 'execColor',
            'rpAset', 'rpHutang', 'qtyTerjual', 'stokAvg', 'rpModalKerja',
            'rpAsetTerjual', 'totalBiayaBeli', 'totalAset', 'hutangTempo',
            'revenueTahunan', 'modalKerja', 'chartLabels', 'historicalData',
            'futureData', 'leadTime', 'avgDailyDemand', 'slope', 'inventoryEfficiency',
            'capitalEfficiency'
        );
    }
}

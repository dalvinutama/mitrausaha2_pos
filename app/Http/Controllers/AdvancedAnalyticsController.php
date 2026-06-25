<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\AnalyticsService;

class AdvancedAnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function generateReport(Request $request)
    {
        $range = (int) $request->input('range', 30);

        $report = Cache::remember('advanced_analytics_data_' . $range, 43200, function () use ($range) {
            $data = $this->analyticsService->generate($range);

            return [
                'solvabilitas' => round($data['solvabilitas'], 2),
                'solvabilitas_stat' => $data['solvabilitasStat'],
                'rop' => round($data['rop']),
                'holt_winters' => round($data['forecastHW']),
                'markov' => $data['p_aman_kritis'] * 100,
                'sarima' => round($data['sarima']),
                'monte_carlo' => round($data['peluangHabis']),
                'altman_z' => round($data['zScoreAltman'], 2),
                'eoq' => round($data['eoq']),
                'var' => $data['korelasi'],
                'weibull' => round($data['weibullRisk'], 1),
                'bayesian' => round($data['bayesianScore']),
                'velocity' => $data['velocity'],
                'dio' => $data['daysInventoryOutstanding'],
                'dio_text' => $data['dioText'],
                'dio_dampener' => round($data['dioDampener'] * 100),
                'capital_return' => round($data['capitalReturnRatio'] * 100, 1),
                'kesimpulan_id' => $data['kesimpulan_id'],
                'kesimpulan_en' => $data['kesimpulan_en'],
                'status' => $data['status'],
                'overall_score' => $data['overallScore'],
                'exec_id' => $data['execId'],
                'exec_en' => $data['execEn'],
                'exec_color' => $data['execColor'],
                'details_id' => [
                    'solvabilitas' => "<b>Data Historis Nyata:</b><br>Total Aset: {$data['rpAset']}<br>Hutang Tempo: {$data['rpHutang']}<br>Penjualan Tahunan: {$data['rpAsetTerjual']}<br>Barang Keluar (6 bln): {$data['qtyTerjual']} unit<br>Stok Gudang: {$data['stokAvg']} unit<br><br><b>Metrik Utama:</b><br>• Days Inventory Outstanding: <b>{$data['dioText']}</b><br>• Return on Asset: <b>" . round($data['capitalReturnRatio'] * 100, 1) . "%</b><br>• Inventory Turnover: <b>" . round($data['solvabilitas'] / max(1, $data['capitalReturnRatio']), 3) . "</b><br><br><b>Maksudnya:</b> DIO mengukur berapa lama modal Anda terpendam di gudang sebelum laku. Makin kecil makin sehat. ROA mengukur seberapa efektif aset menghasilkan uang.<br><br><b>Rekomendasi:</b> " . ($data['daysInventoryOutstanding'] > 180 ? "Stok menumpuk terlalu lama! Segera obral dan stop pembelian barang yang tidak laku." : "Perputaran stok cukup baik. Pertahankan!"),
                    'zscore' => "<b>Data Historis Nyata:</b><br>Modal Kerja: {$data['rpModalKerja']}<br>Total Aset: {$data['rpAset']}<br>Hutang Tempo: {$data['rpHutang']}<br><br><b>Formula Z-Score Modifikasi:</b> = <b>" . round($data['zScoreAltman'], 2) . "</b><br><br><b>Maksudnya:</b> Mengukur seberapa aman toko Anda dari risiko likuiditas gagal bayar hutang berdasarkan pendapatan riil setahun terakhir.<br><br><b>Rekomendasi:</b> " . ($data['zScoreAltman'] > 1.8 ? "Keuangan toko Anda stabil, aman dari risiko likuiditas." : "WASPADA! Jangan tambah hutang PO baru bulan ini!"),
                    'monte_carlo' => "<b>Formula:</b> Sistem mensimulasikan 100 kemungkinan acak (Random Walk & Normal Distribution) untuk 30 hari ke depan, berdasarkan Mean & Variansi transaksi harian asli Anda.<br><br><b>Maksudnya:</b> Angka " . round($data['peluangHabis']) . "% adalah probabilitas pasti bahwa stok Anda akan kosong melompong (Stockout) bulan depan.<br><br><b>Rekomendasi:</b> " . ($data['peluangHabis'] > 50 ? "Sangat Berbahaya! Barang laku keras tapi stok menipis. Segera pesan ke Supplier!" : "Aman. Peluang stok habis kecil."),
                    'eoq' => "<b>Stochastic EOQ Aktual = " . round($data['eoq']) . " unit</b><br><br>Ini adalah jumlah pesanan yang dihitung dari rata-rata barang keluar (" . round($data['avgDailyDemand'], 1) . " unit/hari) dengan mempertimbangkan asumsi Biaya Pesan (Rp50.000) dan Biaya Simpan (5% nilai aset). Pesanlah minimal sebanyak angka ini untuk efisiensi maksimal!",
                    'rop' => "<b>Reorder Point (ROP) = " . round($data['rop']) . " unit</b><br><br>Dihitung dari <b>Lead Time aktual rata-rata (" . round($data['leadTime'], 1) . " hari)</b> dari riwayat pesanan (PO) Anda, ditambah *safety stock* berbasis variansi harian. Jika total stok mendekati angka ini, Anda HARUS telepon supplier hari itu juga.",
                    'hw_sarima' => "<b>Regresi Linear + Penyesuaian Stok</b><br><br>Sistem membaca tren harian $range hari terakhir (Least Squares). Tapi karena stok menumpuk ({$data['dioText']}), forecast dikoreksi ke bawah dengan faktor " . round($data['dioDampener'] * 100) . "% untuk hasil realistis. Prediksi kebutuhan: <b>" . round($data['forecastHW']) . " unit</b>.",
                    'weibull' => "<b>Barang Menua (Dead Stock)</b><br><br>Menunjukkan <b>" . round($data['weibullRisk'], 1) . "%</b> produk di gudang Anda <b>sama sekali tidak terjual</b> dalam 6 bulan terakhir. Ini memakan modal!<br><br><b>Rekomendasi:</b> " . ($data['weibullRisk'] > 10 ? "Segera obral diskon besar-besaran untuk barang lama (Dead Stock)!" : "Rotasi barang (FIFO) Anda sangat bagus, persentase barang mati rendah."),
                ],
                'details_en' => [
                    'solvabilitas' => "<b>Actual Historical Data:</b><br>Total Assets: {$data['rpAset']}<br>Due Debt: {$data['rpHutang']}<br>Annual Revenue: {$data['rpAsetTerjual']}<br>Sold (6 mo): {$data['qtyTerjual']} units<br>Stock: {$data['stokAvg']} units<br><br><b>Key Metrics:</b><br>• Days Inventory Outstanding: <b>{$data['dioText']}</b><br>• Return on Asset: <b>" . round($data['capitalReturnRatio'] * 100, 1) . "%</b><br><br><b>Meaning:</b> DIO measures how long your capital stays in inventory. Lower is better. ROA measures asset efficiency.<br><br><b>Recommendation:</b> " . ($data['daysInventoryOutstanding'] > 180 ? "Stock too long! Clearance sale now!" : "Good turnover. Keep it up!"),
                    'zscore' => "<b>Actual Historical Data:</b><br>Working Capital: {$data['rpModalKerja']}<br>Total Assets: {$data['rpAset']}<br>Due Debt: {$data['rpHutang']}<br><br><b>Modified Z-Score:</b> = <b>" . round($data['zScoreAltman'], 2) . "</b><br><br><b>Meaning:</b> Measures bankruptcy and liquidity risk based on past year real revenue.<br><br><b>Recommendation:</b> " . ($data['zScoreAltman'] > 1.8 ? "Store finances are stable." : "WARNING! Do not add new PO debt this month!"),
                    'monte_carlo' => "<b>Formula:</b> Simulated 100 random scenarios for the next 30 days based on your actual daily mean & variance.<br><br><b>Meaning:</b> This " . round($data['peluangHabis']) . "% is the probability your stock will be completely empty (Stockout) next month.<br><br><b>Recommendation:</b> " . ($data['peluangHabis'] > 50 ? "Very Dangerous! Order immediately!" : "Safe. Stockout probability is low."),
                    'eoq' => "<b>Actual Stochastic EOQ = " . round($data['eoq']) . " units</b><br><br>Calculated from an average outgoing demand of " . round($data['avgDailyDemand'], 1) . " units/day. Order at least this amount for maximum cost efficiency!",
                    'rop' => "<b>Reorder Point (ROP) = " . round($data['rop']) . " units</b><br><br>Calculated from actual average Lead Time of " . round($data['leadTime'], 1) . " days from your PO history, plus safety stock. Reorder when stock hits this.",
                    'hw_sarima' => "<b>Linear Regression + Stock Adjustment</b><br><br>Analyzed $range days of real transactions. Since stock is piling up ({$data['dioText']}), the forecast is down-adjusted by " . round($data['dioDampener'] * 100) . "%. Predicted demand: <b>" . round($data['forecastHW']) . " units</b>.",
                    'weibull' => "<b>Dead Stock Risk</b><br><br>Shows <b>" . round($data['weibullRisk'], 1) . "%</b> of inventory has not sold a single unit in the last 6 months.<br><br><b>Recommendation:</b> " . ($data['weibullRisk'] > 10 ? "Immediately run massive discount sales!" : "Good item rotation."),
                ],
                'chart' => [
                    'labels' => $data['chartLabels'],
                    'history' => $data['historicalData'],
                    'future' => $data['futureData'],
                ],
            ];
        });

        return response()->json($report);
    }
}

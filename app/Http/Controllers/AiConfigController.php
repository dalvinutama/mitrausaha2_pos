<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAiConfigRequest;
use App\Models\AiConfig;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AiConfigController extends Controller
{
    public function getConfig()
    {
        $config = DB::table('ai_configs')->first();
        if (!$config) {
            return response()->json(['error' => 'Config not found'], 404);
        }

        // Calculate math for a sample product
        // Prefer a product that actually has sales history to show real math
        $product = Product::whereHas('transactionItems', function($q) {
            $q->whereHas('transaction', function($t) {
                $t->where('jenis_transaksi', 'keluar')->where('tanggal', '>=', Carbon::now()->subDays(30));
            });
        })->inRandomOrder()->first();

        // If no product has sales in last 30 days, just pick any
        if (!$product) {
            $product = Product::inRandomOrder()->first();
        }

        if (!$product) {
            $product = (object)[
                'id' => 0,
                'nama_barang' => 'Sample Product (Dummy)',
                'harga_beli' => 100000,
                'stok' => 50
            ];
        }

        // 1. Lead Time Math
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $pos = Transaction::where('jenis_transaksi', 'po')
            ->whereNotNull('estimasi_datang')
            ->where('tanggal', '>=', $sixMonthsAgo)
            ->get();
        
        $totalLeadTimeDays = 0;
        $poCount = 0;
        foreach ($pos as $po) {
            $diff = Carbon::parse($po->tanggal)->diffInDays(Carbon::parse($po->estimasi_datang));
            if ($diff >= 0) {
                $totalLeadTimeDays += $diff;
                $poCount++;
            }
        }
        $isFallbackLt = false;
        if ($poCount > 0) {
            $leadTime = max(1, $totalLeadTimeDays / $poCount);
        } else {
            $leadTime = 3;
            $isFallbackLt = true;
        }

        // 2. Demand & ROP Math
        $daysAgo = Carbon::now()->subDays(30);
        $dailyDemand = [];
        if (isset($product->id) && $product->id != 0) {
            $dailyDemand = TransactionItem::where('product_id', $product->id)
                ->whereHas('transaction', function($q) use ($daysAgo) {
                    $q->where('jenis_transaksi', 'keluar')->where('tanggal', '>=', $daysAgo->format('Y-m-d'));
                })
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(qty) as total'))
                ->groupBy('date')
                ->pluck('total', 'date')->toArray();
        }

        $demandArr = [];
        for ($i = 30; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->format('Y-m-d');
            $demandArr[] = isset($dailyDemand[$d]) ? $dailyDemand[$d] : 0;
        }

        $totalDemand30Days = array_sum($demandArr);
        
        $isFallbackDemand = false;
        if ($totalDemand30Days > 0) {
            $avgDemand = $totalDemand30Days / 30;
        } else {
            $avgDemand = 0.5; // Fallback to prevent 0 math
            $isFallbackDemand = true;
        }

        $variance = 0;
        if (count($demandArr) > 1 && !$isFallbackDemand) {
            $sqDiffs = array_map(function($x) use ($avgDemand) { return pow($x - $avgDemand, 2); }, $demandArr);
            $variance = array_sum($sqDiffs) / (count($demandArr) - 1);
        } else if ($isFallbackDemand) {
            $variance = 0.26; // Fallback variance
        }

        $zScore = config('bisnis.z_score');
        $safetyStock = $zScore * sqrt($leadTime * $variance);
        $rop = ($avgDemand * $leadTime) + $safetyStock;

        // 3. EOQ Math
        $annualDemand = $totalDemand30Days * 12;
        if ($isFallbackDemand) {
            $annualDemand = 10;
        }
        
        $biayaPesan = $config->biaya_pesan;
        $biayaSimpan = max(500, $product->harga_beli * $config->biaya_simpan_persen);
        
        $eoq = sqrt((2 * $annualDemand * $biayaPesan) / $biayaSimpan);

        return response()->json([
            'config' => $config,
            'math' => [
                'sample_product' => $product->nama_barang,
                'has_fallback_demand' => $isFallbackDemand,
                'has_fallback_lt' => $isFallbackLt,
                'lead_time' => [
                    'total_days' => $totalLeadTimeDays,
                    'po_count' => $poCount,
                    'avg' => round($leadTime, 2)
                ],
                'demand' => [
                    'total_30d' => $totalDemand30Days,
                    'days' => 30,
                    'avg' => round($avgDemand, 2),
                    'variance' => round($variance, 2)
                ],
                'rop' => [
                    'z_score' => $zScore,
                    'safety_stock' => round($safetyStock, 2),
                    'final' => round($rop)
                ],
                'eoq' => [
                    'annual_demand' => round($annualDemand),
                    'ordering_cost' => $biayaPesan,
                    'holding_cost' => $biayaSimpan,
                    'final' => round($eoq)
                ]
            ]
        ]);
    }

    public function updateConfig(UpdateAiConfigRequest $request)
    {
        DB::table('ai_configs')->where('id', 1)->update([
            'auto_po_active' => $request->auto_po_active,
            'daily_check_active' => $request->daily_check_active,
            'biaya_pesan' => $request->biaya_pesan,
            'updated_at' => Carbon::now()
        ]);

        return response()->json(['success' => true, 'message' => 'Konfigurasi AI berhasil diperbarui']);
    }
}

<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryService
{
    /**
     * Menghitung ulang nilai ROP dan EOQ untuk semua produk
     */
    public function recalculateAll()
    {
        $products = Product::all();
        $updatedCount = 0;

        foreach ($products as $product) {
            $this->recalculateForProduct($product);
            $updatedCount++;
        }

        return $updatedCount;
    }

    /**
     * Menghitung ulang ROP dan EOQ untuk 1 produk spesifik secara Cerdas (True Dynamic)
     */
    public function recalculateForProduct(Product $product)
    {
        $evaluationPeriodDays = 180;
        $periodStartDate = Carbon::now()->subDays($evaluationPeriodDays);

        // ==========================================
        // 1. ANALISIS DEMAND (d_avg & d_max)
        // ==========================================
        $salesData = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transaction_items.product_id', $product->id)
            ->where('transactions.jenis_transaksi', 'keluar')
            ->where('transactions.tanggal', '>=', $periodStartDate)
            ->select(
                DB::raw('DATE(transactions.tanggal) as date'),
                DB::raw('SUM(transaction_items.qty) as daily_qty')
            )
            ->groupBy('date')
            ->get();

        $totalTerjual = $salesData->sum('daily_qty');
        $maxDailyQty = $salesData->max('daily_qty') ?? 0;
        $avgDailyDemand = $totalTerjual / $evaluationPeriodDays;

        // ==========================================
        // 2. ANALISIS LEAD TIME MACHINE LEARNING (L_avg & L_max)
        // ==========================================
        // Cari 5 histori Purchase Order (PO) terakhir yang memuat produk ini dan sudah selesai (Barang Masuk)
        $historicalPOs = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transaction_items.product_id', $product->id)
            ->where('transactions.jenis_transaksi', 'po')
            ->where('transactions.status', 'selesai')
            ->select('transactions.tanggal', 'transactions.updated_at')
            ->orderBy('transactions.tanggal', 'desc')
            ->limit(5)
            ->get();

        $l_avg = 1;
        $l_max = 1;

        if ($historicalPOs->count() > 0) {
            // Kita punya data riwayat pengiriman!
            $totalDays = 0;
            $maxDays = 0;

            foreach ($historicalPOs as $po) {
                $poDate = Carbon::parse($po->tanggal)->startOfDay();
                $receivedDate = Carbon::parse($po->updated_at)->startOfDay(); // Asumsi updated_at adalah waktu barang diterima
                
                $days = $poDate->diffInDays($receivedDate);
                if ($days < 1) $days = 1; // Minimal 1 hari

                $totalDays += $days;
                if ($days > $maxDays) {
                    $maxDays = $days;
                }
            }

            $l_avg = $totalDays / $historicalPOs->count();
            $l_max = $maxDays;
        } else {
            // Fallback: Belum ada riwayat PO, gunakan tebakan admin dari Master Data
            $l_avg = $product->lead_time_hari > 0 ? $product->lead_time_hari : 1;
            // Untuk L_max, kita asumsikan bisa molor 2 hari dari janji admin sebagai safety net
            $l_max = $l_avg + 2; 
        }

        // ==========================================
        // 3. KALKULASI SAFETY STOCK (SS) KONDISI D
        // ==========================================
        // Rumus: (d_max * L_max) - (d_avg * L_avg)
        if ($product->tipe_safety_stock == 'otomatis') {
            $calculatedSafetyStock = ($maxDailyQty * $l_max) - ($avgDailyDemand * $l_avg);
            $finalSafetyStock = max(0, ceil($calculatedSafetyStock));
        } else {
            // Jika manual, gunakan nilai keras yang diinput user
            $finalSafetyStock = $product->safety_stock;
        }

        // ==========================================
        // 4. KALKULASI REORDER POINT (ROP)
        // ==========================================
        // Rumus: (d_avg * L_avg) + SS
        $rop = ceil(($avgDailyDemand * $l_avg) + $finalSafetyStock);

        // ==========================================
        // 5. KALKULASI ECONOMIC ORDER QUANTITY (EOQ)
        // ==========================================
        // Rumus: SQRT( (2 * D * S) / H )
        $annualDemand = $avgDailyDemand * 365;
        $orderingCost = config('bisnis.ordering_cost');
        $holdingCostPercent = config('bisnis.holding_cost_percent');
        
        $holdingCost = $product->harga_beli * $holdingCostPercent;
        if ($holdingCost <= 0) $holdingCost = 1000; // Fallback anti division by zero

        if ($annualDemand > 0) {
            $eoq = sqrt((2 * $annualDemand * $orderingCost) / $holdingCost);
            $finalEoq = ceil($eoq);
        } else {
            $finalEoq = 0; // Barang mati, tidak perlu pesan
        }

        // ==========================================
        // 6. UPDATE MASTER DATA
        // ==========================================
        $product->update([
            'safety_stock' => $finalSafetyStock,
            'reorder_point' => $rop,
            'eoq' => $finalEoq
        ]);

        return [
            'rop' => $rop,
            'eoq' => $finalEoq,
            'safety_stock' => $finalSafetyStock,
            'd_avg' => $avgDailyDemand,
            'd_max' => $maxDailyQty,
            'L_avg' => $l_avg,
            'L_max' => $l_max
        ];
    }
}

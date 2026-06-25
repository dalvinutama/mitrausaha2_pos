<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryAutoPo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:auto-po';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically draft Purchase Orders for products that fall below ROP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Scanning inventory for Auto-PO...");

        $config = DB::table('ai_configs')->first();
        if ($config && !$config->auto_po_active) {
            $this->warn("Auto-PO is disabled by Owner. Aborting.");
            return;
        }

        $products = Product::all();
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        // Fetch global average lead time
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
        $leadTime = max(1, $poCount > 0 ? ($totalLeadTimeDays / $poCount) : 3);

        $draftedCount = 0;

        foreach ($products as $product) {
            // Check past 30 days demand for this product
            $daysAgo = Carbon::now()->subDays(30);
            
            $dailyDemand = TransactionItem::where('product_id', $product->id)
                ->whereHas('transaction', function($q) use ($daysAgo) {
                    $q->where('jenis_transaksi', 'keluar')->where('tanggal', '>=', $daysAgo->format('Y-m-d'));
                })
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(qty) as total'))
                ->groupBy('date')
                ->pluck('total', 'date')->toArray();

            $demandArr = [];
            for ($i = 30; $i >= 0; $i--) {
                $d = Carbon::now()->subDays($i)->format('Y-m-d');
                $demandArr[] = isset($dailyDemand[$d]) ? $dailyDemand[$d] : 0;
            }

            $avgDemand = count($demandArr) > 0 ? array_sum($demandArr)/count($demandArr) : 0;
            if ($avgDemand <= 0) continue; // No demand, no need to auto-PO

            $variance = 0;
            if (count($demandArr) > 1) {
                $sqDiffs = array_map(function($x) use ($avgDemand) { return pow($x - $avgDemand, 2); }, $demandArr);
                $variance = array_sum($sqDiffs) / (count($demandArr) - 1);
            }

            $rop = round(($avgDemand * $leadTime) + (1.65 * sqrt($leadTime * $variance)));

            // If stock is below or equal to ROP, we need to order
            if ($product->stok <= max(1, $rop)) {
                // Check if we already have an active/pending PO for this item to avoid duplicates
                $existingPO = TransactionItem::where('product_id', $product->id)
                    ->whereHas('transaction', function($q) {
                        $q->where('jenis_transaksi', 'po')->where('status', 'pending');
                    })->exists();

                if ($existingPO) continue;

                // Calculate EOQ
                $totalOut = array_sum($demandArr);
                $annualDemand = $totalOut * 12; // annualized from 1 month
                if ($annualDemand <= 0) $annualDemand = 10;
                
                $biayaPesan = $config ? $config->biaya_pesan : 50000;
                $biayaSimpanPersen = $config ? $config->biaya_simpan_persen : 0.05;
                $biayaSimpan = max(500, $product->harga_beli * $biayaSimpanPersen);
                $eoq = round(sqrt((2 * $annualDemand * $biayaPesan) / $biayaSimpan));
                $qtyToOrder = max($eoq, 10); // order at least 10 or eoq

                // Find last supplier for this product
                $lastPoItem = TransactionItem::where('product_id', $product->id)
                    ->whereHas('transaction', function($q) {
                        $q->where('jenis_transaksi', 'po');
                    })->latest('id')->first();
                
                $supplierId = null;
                if ($lastPoItem && $lastPoItem->transaction) {
                    $supplierId = $lastPoItem->transaction->supplier_id;
                } else {
                    $firstSupplier = DB::table('suppliers')->first();
                    $supplierId = $firstSupplier ? $firstSupplier->id : null;
                }

                if (!$supplierId) continue;

                // Create Draft PO
                $no_po = 'PO-AUTO-' . time() . '-' . $product->id;
                $subtotal = $qtyToOrder * $product->harga_beli;

                $po = Transaction::create([
                    'no_transaksi' => $no_po,
                    'jenis_transaksi' => 'po',
                    'tanggal' => Carbon::now()->format('Y-m-d'),
                    'supplier_id' => $supplierId,
                    'tipe_pembayaran' => 'tempo',
                    'total_nilai' => $subtotal,
                    'status' => 'pending',
                    'catatan' => 'Auto-generated by System based on ROP limits.'
                ]);

                TransactionItem::create([
                    'transaction_id' => $po->id,
                    'product_id' => $product->id,
                    'qty' => $qtyToOrder,
                    'harga_satuan' => $product->harga_beli,
                    'subtotal' => $subtotal
                ]);

                $draftedCount++;

                // Create Notification
                DB::table('notifications')->insert([
                    'id' => Str::uuid()->toString(),
                    'type' => 'App\Notifications\SystemAlert',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => 1, // Assuming admin ID 1
                    'data' => json_encode([
                        'title' => 'Sistem Auto-PO Aktif',
                        'message' => "Sistem telah membuat Draf PO ($no_po) untuk " . $product->nama_barang . " karena stok menipis.",
                        'url' => '/purchase_order'
                    ]),
                    'read_at' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        $this->info("Completed! Auto-drafted $draftedCount POs.");
    }
}

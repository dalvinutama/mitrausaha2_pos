<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryDailyCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:daily-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run daily health checks: flag late POs and find dead stock.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Daily Health Check...");

        $config = DB::table('ai_configs')->first();
        if ($config && !$config->daily_check_active) {
            $this->warn("Daily Check is disabled by Owner. Aborting.");
            return;
        }

        // 1. Flag Late POs
        $latePOs = Transaction::where('jenis_transaksi', 'po')
            ->where('status', 'pending')
            ->whereNotNull('estimasi_datang')
            ->where('estimasi_datang', '<', Carbon::now()->format('Y-m-d'))
            ->get();
        
        $adminUsers = User::whereIn('role', ['admin', 'owner'])->get();

        foreach ($latePOs as $po) {
            // Send notification to all admins
            foreach ($adminUsers as $admin) {
                DB::table('notifications')->insert([
                    'id' => Str::uuid()->toString(),
                    'type' => 'App\Notifications\SystemAlert',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $admin->id,
                    'data' => json_encode([
                        'title' => 'PO Terlambat!',
                        'message' => "Purchase Order {$po->no_transaksi} melewati estimasi pengiriman (" . Carbon::parse($po->estimasi_datang)->format('d M') . "). Segera hubungi supplier!",
                        'url' => '/purchase_order'
                    ]),
                    'read_at' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        $this->info("Flagged " . $latePOs->count() . " late POs.");

        // 2. Dead Stock Check (Products with no sales in 6 months)
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $activeProductIds = TransactionItem::whereHas('transaction', function($q) use ($sixMonthsAgo) {
            $q->where('jenis_transaksi', 'keluar')->where('tanggal', '>=', $sixMonthsAgo->format('Y-m-d'));
        })->pluck('product_id')->unique()->toArray();

        // Get products with stock > 0 that haven't moved
        $deadStocks = Product::whereNotIn('id', $activeProductIds)
            ->where('stok', '>', 0)
            ->get();

        if ($deadStocks->count() > 0) {
            foreach ($adminUsers as $admin) {
                DB::table('notifications')->insert([
                    'id' => Str::uuid()->toString(),
                    'type' => 'App\Notifications\SystemAlert',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $admin->id,
                    'data' => json_encode([
                        'title' => 'Peringatan Dead Stock',
                        'message' => "Ditemukan " . $deadStocks->count() . " produk yang tidak terjual sama sekali dalam 6 bulan terakhir. Pertimbangkan obral/diskon.",
                        'url' => '/persediaan'
                    ]),
                    'read_at' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        $this->info("Found " . $deadStocks->count() . " dead stock items.");

        $this->info("Daily Health Check Completed!");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use App\Models\StoreProfile;
use App\Models\AuditLog;

class BackfillAuditLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:backfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill existing database records into the audit_logs table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses backfill (penarikan data lama) ke Audit Log...');
        
        $models = [
            Product::class,
            Kategori::class,
            Supplier::class,
            Transaction::class,
            User::class,
            StoreProfile::class
        ];

        $totalCount = 0;

        foreach($models as $modelClass) {
            $records = $modelClass::all();
            $moduleName = class_basename($modelClass);
            $count = 0;
            
            foreach($records as $record) {
                // Pastikan belum ada log CREATE untuk record ini
                $exists = AuditLog::where('module', $moduleName)
                    ->where('record_id', $record->getKey())
                    ->where('action', 'CREATE')
                    ->exists();

                if(!$exists) {
                    // Coba ambil nama field untuk deskripsi
                    $nameField = $record->name ?? $record->title ?? $record->nama_barang ?? $record->nama_kategori ?? $record->nama_supplier ?? $record->nama_toko ?? '';
                    $identifier = $nameField ? " ($nameField)" : "";
                    
                    AuditLog::create([
                        'user_id' => null, // Oleh sistem
                        'action' => 'CREATE',
                        'module' => $moduleName,
                        'description' => "CREATE {$moduleName} ID: " . $record->getKey() . $identifier . " (Data Lama/Inisialisasi Sistem)",
                        'old_values' => null,
                        'new_values' => $record->getAttributes(),
                        'record_id' => $record->getKey(),
                        'created_at' => $record->created_at ?? now(),
                        'updated_at' => $record->created_at ?? now(),
                    ]);
                    $count++;
                    $totalCount++;
                }
            }
            $this->info("Berhasil menarik $count data lama dari modul $moduleName.");
        }
        
        $this->info("Selesai! Total $totalCount data lama berhasil dimasukkan ke Audit Trail.");
    }
}

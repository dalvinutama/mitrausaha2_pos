<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'products', 'suppliers', 'transactions', 'transaction_items',
        'transaction_payments', 'kategoris', 'kategori_outbounds',
        'satuans', 'users', 'stock_opnames', 'stock_opname_details',
        'store_profiles',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function ($t) {
                    $t->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function ($t) {
                    $t->dropSoftDeletes();
                });
            }
        }
    }
};

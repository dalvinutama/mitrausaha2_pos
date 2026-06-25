<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_po_active')->default(true);
            $table->boolean('daily_check_active')->default(true);
            $table->integer('biaya_pesan')->default(50000);
            $table->float('biaya_simpan_persen')->default(0.05); // 5% dari harga beli
            $table->timestamps();
        });

        // Insert default row
        DB::table('ai_configs')->insert([
            'auto_po_active' => true,
            'daily_check_active' => true,
            'biaya_pesan' => 50000,
            'biaya_simpan_persen' => 0.05,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_configs');
    }
};

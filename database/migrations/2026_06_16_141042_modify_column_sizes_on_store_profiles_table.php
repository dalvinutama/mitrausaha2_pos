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
        if (DB::getDriverName() !== 'sqlite') {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->string('nama_toko', 100)->default('Mitra Usaha 2 Pontianak')->change();
            $table->string('tagline', 150)->default('Distributor & Retail Bahan Bangunan Terlengkap')->change();
            $table->string('telepon', 20)->default('(0561) 123456')->change();
            $table->string('kota_ttd', 50)->default('Pontianak')->change();
            $table->string('nama_kepala_gudang', 75)->nullable()->default('')->change();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
        Schema::table('store_profiles', function (Blueprint $table) {
            $table->string('nama_toko', 255)->default('Mitra Usaha 2 Pontianak')->change();
            $table->string('tagline', 255)->default('Distributor & Retail Bahan Bangunan Terlengkap')->change();
            $table->string('telepon', 255)->default('(0561) 123456')->change();
            $table->string('kota_ttd', 255)->default('Pontianak')->change();
            $table->string('nama_kepala_gudang', 255)->nullable()->default('')->change();
        });
        }
    }
};

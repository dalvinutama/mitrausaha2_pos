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
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 30)->change();
            $table->string('barcode', 30)->nullable()->change();
            $table->string('nama_barang', 150)->change();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku', 255)->change();
            $table->string('barcode', 255)->nullable()->change();
            $table->string('nama_barang', 255)->change();
        });
        }
    }
};

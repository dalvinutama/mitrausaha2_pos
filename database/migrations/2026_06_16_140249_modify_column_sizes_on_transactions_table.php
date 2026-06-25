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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('no_transaksi', 50)->change();
            $table->string('no_referensi', 100)->nullable()->change();
            $table->string('tujuan', 150)->nullable()->change();
            $table->string('kategori_keluar', 50)->nullable()->change();
            $table->string('tipe_pembayaran', 50)->nullable()->change();
            $table->string('info_pengiriman', 150)->nullable()->change();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('no_transaksi', 255)->change();
            $table->string('no_referensi', 255)->nullable()->change();
            $table->string('tujuan', 255)->nullable()->change();
            $table->string('kategori_keluar', 255)->nullable()->change();
            $table->string('tipe_pembayaran', 255)->nullable()->change();
            $table->string('info_pengiriman', 255)->nullable()->change();
        });
        }
    }
};

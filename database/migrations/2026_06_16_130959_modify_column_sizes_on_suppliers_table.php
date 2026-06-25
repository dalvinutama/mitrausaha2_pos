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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('nama_supplier', 150)->change();
            $table->string('nama_pic', 75)->change();
            $table->string('kategori_suplai', 100)->nullable()->change();
            $table->string('termin_default', 50)->default('cash')->change();
            $table->string('nama_bank', 100)->nullable()->change();
            $table->string('no_rekening', 30)->nullable()->change();
            $table->string('status', 20)->default('Aktif')->change();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('nama_supplier', 255)->change();
            $table->string('nama_pic', 255)->change();
            $table->string('kategori_suplai', 255)->nullable()->change();
            $table->string('termin_default', 255)->default('cash')->change();
            $table->string('nama_bank', 255)->nullable()->change();
            $table->string('no_rekening', 255)->nullable()->change();
            $table->string('status', 255)->default('Aktif')->change();
        });
        }
    }
};

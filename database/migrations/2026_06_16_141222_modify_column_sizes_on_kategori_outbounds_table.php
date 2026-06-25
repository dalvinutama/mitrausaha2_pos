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
        Schema::table('kategori_outbounds', function (Blueprint $table) {
            $table->string('nama_kategori', 75)->change();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
        Schema::table('kategori_outbounds', function (Blueprint $table) {
            $table->string('nama_kategori', 255)->change();
        });
        }
    }
};

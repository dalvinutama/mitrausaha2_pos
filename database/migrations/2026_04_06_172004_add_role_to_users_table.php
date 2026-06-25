<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom role dengan pilihan jabatan yang sudah kita sepakati
            $table->enum('role', ['owner', 'admin', 'penjualan', 'gudang', 'pengiriman', 'kasir'])
                  ->default('kasir') // Default jika tidak diisi
                  ->after('email'); // Posisinya ditaruh setelah kolom email
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
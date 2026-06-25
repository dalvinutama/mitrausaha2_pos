<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel kategoris (restrict agar kategori tidak bisa dihapus jika ada produknya)
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            
            $table->string('sku')->unique()->comment('Gabungan Prefix Kategori + Nomor');
            $table->string('barcode')->nullable()->unique();
            $table->string('nama_barang');
            $table->integer('stok')->default(0);
            
            // =========================================================
            // KOLOM BARU UNTUK SISTEM REORDER POINT (Menggantikan min_stok)
            // =========================================================
            $table->integer('lead_time_hari')->default(1)->comment('Waktu tunggu pesanan datang (hari)');
            $table->enum('tipe_safety_stock', ['manual', 'otomatis'])->default('manual')->comment('Metode cadangan aman');
            $table->integer('safety_stock')->default(0)->comment('Nilai stok pengaman');
            $table->integer('reorder_point')->default(0)->comment('Titik pesan kembali (dihitung otomatis oleh sistem)');
            // =========================================================

            $table->bigInteger('harga_beli')->default(0);
            $table->bigInteger('harga_jual')->default(0); 
            $table->string('satuan', 50); // Pcs, Sak, Kg, dll
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
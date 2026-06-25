<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel header (Jika header dihapus, detail ikut terhapus)
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->onDelete('cascade');
            
            // Relasi ke master barang. 
            // Catatan: Ubah 'products' jika nama tabel master barangmu berbeda (misal 'persediaans')
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            
            // Data komparasi stok
            $table->integer('stok_sistem'); // Stok menurut aplikasi saat dokumen dibuat
            $table->integer('stok_fisik');  // Stok hasil hitungan nyata di gudang
            $table->integer('selisih');     // Fisik - Sistem (Bisa minus/plus)
            
            // Valuasi aset (Menggunakan tipe bigInteger untuk antisipasi nilai rupiah yang besar)
            $table->bigInteger('harga_pokok_snapshot'); // Harga beli barang saat itu
            $table->bigInteger('nilai_selisih');        // selisih * harga_pokok_snapshot
            
            $table->string('keterangan')->nullable(); // Alasan selisih per barang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_details');
    }
};
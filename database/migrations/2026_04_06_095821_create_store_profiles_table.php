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
        Schema::create('store_profiles', function (Blueprint $table) {
            $table->id();
            
            // Profil Utama Toko
            $table->string('nama_toko')->default('Mitra Usaha 2 Pontianak');
            $table->string('tagline')->default('Distributor & Retail Bahan Bangunan Terlengkap');
            $table->text('alamat')->default('Jl. Contoh Pembangunan No. 123, Pontianak, Kalimantan Barat');
            
            // Kontak
            $table->string('telepon')->default('(0561) 123456');
            $table->string('email')->default('admin@mitrausaha2.com');
            
            // Kebutuhan Cetak PDF (Kop & Tanda Tangan)
            $table->string('kota_ttd')->default('Pontianak'); 
            $table->string('nama_kepala_gudang')->nullable()->default(''); 
            
            // INI YANG BARU: Penanda profil mana yang sedang aktif dipakai untuk cetak PDF
            $table->boolean('is_active')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_profiles');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // Otomatis bikin Primary Key (id)
            
            // Info Dasar
            $table->string('nama_supplier');
            $table->text('alamat')->nullable(); // nullable = boleh dikosongkan
            
            // Kontak PIC
            $table->string('nama_pic');
            $table->string('no_hp', 20);
            $table->string('email')->nullable();
            
            // Operasional & Pembayaran
            $table->string('kategori_suplai')->nullable();
            $table->string('termin_default')->default('cash'); 
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();
            
            // Lain-lain
            $table->text('catatan')->nullable();
            $table->string('status')->default('Aktif'); // Otomatis diset 'Aktif'
            
            $table->timestamps(); // Otomatis bikin created_at dan updated_at
        });
    }

    /**
     * Batalkan migration (hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
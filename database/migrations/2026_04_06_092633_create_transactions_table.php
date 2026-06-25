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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // 1. Identitas Utama Transaksi
            $table->string('no_transaksi')->unique(); // Format: BM-..., BK-..., PO-...
            $table->enum('jenis_transaksi', ['masuk', 'keluar', 'po']); 
            $table->date('tanggal'); // Tgl masuk / tgl keluar / tgl pesan PO
            
            // 2. Relasi Database (Foreign Keys)
            // Relasi ke tabel suppliers (Bisa kosong jika ini adalah Barang Keluar)
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');

            // Relasi ke tabel users (Mengetahui Admin/Kasir siapa yang input)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // 3. Data Referensi & Tujuan
            $table->string('no_referensi')->nullable(); // No Faktur Supplier (Masuk) / No SPK (Keluar)
            $table->string('tujuan')->nullable(); // Nama Customer / Proyek (Khusus Keluar)
            $table->string('kategori_keluar')->nullable(); // Penjualan, Proyek, Internal, Retur (Khusus Keluar)

            // 4. Data Spesifik PO & Pembayaran
            $table->date('estimasi_datang')->nullable(); // Khusus PO
            $table->string('tipe_pembayaran')->nullable(); // Cash, Tempo, Net30, dll
            $table->string('info_pengiriman')->nullable(); // Alamat/Instruksi kirim (Khusus PO)

            // 5. Nilai Finansial
            // Menyimpan total transaksi agar loading Laporan lebih cepat (tidak perlu sum isi keranjang terus-menerus)
            $table->decimal('total_nilai', 15, 2)->default(0); 

            // 6. Lain-lain
            $table->text('catatan')->nullable();
            $table->enum('status', ['selesai', 'pending', 'batal'])->default('selesai'); 

            $table->timestamps(); // otomatis buat created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
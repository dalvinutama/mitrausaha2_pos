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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();

            // 1. Relasi ke Header Transaksi (Kop Surat)
            // onDelete('cascade') berarti jika nota transaksi dihapus, semua isi keranjangnya ikut terhapus otomatis
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');

            // 2. Relasi ke Master Data Persediaan
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // 3. Detail Kuantitas (Fisik)
            $table->integer('qty'); // Qty yang dipesan / masuk / keluar
            $table->integer('qty_rusak')->default(0); // Qty rusak/retur saat penerimaan (Khusus Barang Masuk)

            // 4. Detail Finansial
            // Menggunakan decimal agar akurat untuk nilai uang yang besar
            $table->decimal('harga_satuan', 15, 2)->default(0); // Harga Beli (Masuk/PO) atau Harga Jual (Keluar)
            $table->decimal('diskon', 15, 2)->default(0); // Potongan harga per item (Khusus PO/Keluar)
            $table->decimal('subtotal', 15, 2)->default(0); // Hasil dari: (qty * harga_satuan) - diskon

            // 5. Data Tambahan (Opsional)
            $table->string('spesifikasi')->nullable(); // Catatan spek khusus merek/warna (Khusus PO)
            $table->date('tgl_expired')->nullable(); // Masa kedaluwarsa material (Khusus Barang Masuk)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            
            // Global Settings
            $table->string('logo')->nullable();
            $table->string('nama_toko')->default('TB. MITRA USAHA 2');
            $table->string('primary_color')->default('#ef4444');
            $table->string('header_color')->default('#fef2f2');
            $table->text('footer_text')->nullable();

            // Low Stock
            $table->string('low_stock_title')->default('🚨 PERINGATAN STOK KRITIS');
            $table->text('low_stock_intro')->nullable();
            $table->text('low_stock_outro')->nullable();
            $table->string('low_stock_btn')->default('Buat PO Sekarang');

            // PO New
            $table->string('po_new_title')->default('Pemberitahuan Purchase Order');
            $table->text('po_new_intro')->nullable();
            $table->text('po_new_outro')->nullable();
            $table->string('po_new_btn')->default('Buka Aplikasi Sekarang');

            // PO Digest
            $table->string('po_digest_title')->default('Ringkasan PO Harian');
            $table->text('po_digest_intro')->nullable();

            // Header Notification
            $table->string('sys_notif_title')->default('Pemberitahuan Sistem Baru');
            $table->text('sys_notif_intro')->nullable();

            $table->timestamps();
        });

        // Insert default row
        DB::table('email_settings')->insert([
            'nama_toko' => 'TB. MITRA USAHA 2',
            'primary_color' => '#ef4444',
            'header_color' => '#fef2f2',
            'footer_text' => 'Ini adalah pesan otomatis yang dihasilkan oleh Sistem Manajemen TB Mitra Usaha 2. Harap tidak membalas email ini.',
            'low_stock_intro' => 'Sistem mendeteksi bahwa stok salah satu barang di gudang baru saja menyentuh atau berada di bawah Batas Aman (Reorder Point) akibat adanya transaksi barang keluar hari ini.',
            'low_stock_outro' => 'Mohon segera lakukan Purchase Order (PO) kepada Supplier untuk menghindari kekosongan barang yang dapat mengganggu operasional toko.',
            'po_new_intro' => 'Terdapat dokumen Purchase Order baru yang baru saja dicatat ke dalam sistem. Berikut adalah rincian transaksinya:',
            'po_new_outro' => 'Silakan segera masuk ke dalam sistem aplikasi untuk meninjau lebih detail dan memproses transaksi ini jika diperlukan.',
            'po_digest_intro' => 'Berikut adalah rekapitulasi data Purchase Order yang terjadi pada hari ini.',
            'sys_notif_intro' => 'Terdapat pemberitahuan sistem baru untuk Anda.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};

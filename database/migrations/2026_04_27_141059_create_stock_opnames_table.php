<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('no_opname')->unique();
            $table->date('tanggal');
            $table->string('periode'); // Contoh format: "Oktober 2026"
            
            // Relasi ke user yang membuat dokumen (Gudang/Admin)
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('restrict');
            
            // Relasi ke user yang menyetujui (Owner)
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            
            // Status dokumen
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('draft');
            
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
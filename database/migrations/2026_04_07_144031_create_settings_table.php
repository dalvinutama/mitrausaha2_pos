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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('logo')->nullable(); // Untuk menyimpan nama file foto logo
            $table->boolean('is_active')->default(false); // Penanda toko mana yang aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
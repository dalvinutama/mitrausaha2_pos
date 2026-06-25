<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // ID Pengirim Pesan
            $table->unsignedBigInteger('from_user_id');
            
            // Isi Pesan
            $table->text('content');
            
            $table->timestamps();

            // Relasi ke tabel users (agar tahu siapa yang ngirim)
            // Jika user dihapus, pesannya ikut terhapus (cascade)
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
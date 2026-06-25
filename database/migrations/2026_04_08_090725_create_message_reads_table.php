<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('message_reads', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke pesan yang dibaca
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            
            // Relasi ke user yang membaca pesan tersebut
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // created_at akan otomatis menjadi penanda "Kapan pesan ini dibaca"
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('message_reads');
    }
};
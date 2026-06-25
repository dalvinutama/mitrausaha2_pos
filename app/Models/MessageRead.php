<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{
    use HasFactory;

    protected $fillable = ['message_id', 'user_id'];

    // Relasi balik ke pesan
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    // Relasi ke user yang membaca
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
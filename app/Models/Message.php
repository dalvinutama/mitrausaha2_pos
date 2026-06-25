<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // Pastikan to_user_id dan is_read sudah tidak ada di fillable
    protected $fillable = [
        'from_user_id', 
        'conversation_id',
        'content',
        'type',
        'reply_to_id',
        'is_pinned'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    // Relasi ke Pengirim Pesan
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // INI METHOD YANG HILANG: Relasi ke tabel message_reads
    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class StoreProfile extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    // Mengizinkan kolom-kolom ini untuk diisi dan diubah secara massal dari form web
    protected $fillable = [
        'nama_toko', 
        'tagline', 
        'alamat', 
        'telepon', 
        'email', 
        'kota_ttd', 
        'nama_kepala_gudang',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
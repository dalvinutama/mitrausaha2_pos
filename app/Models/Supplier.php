<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    // Wajib ditambahkan agar form bisa disimpan (Mass Assignment)
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'nama_pic',
        'no_hp',
        'email',
        'kategori_suplai',
        'termin_default',
        'nama_bank',
        'no_rekening',
        'catatan',
        'status',
    ];
}
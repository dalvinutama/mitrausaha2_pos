<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Kategori extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'prefix_sku',
        'nama_kategori',
        'deskripsi'
    ];
}
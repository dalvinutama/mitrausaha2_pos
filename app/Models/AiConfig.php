<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiConfig extends Model
{
    use HasFactory;

    protected $casts = [
        'auto_po_active' => 'boolean',
        'daily_check_active' => 'boolean',
        'biaya_pesan' => 'integer',
        'biaya_simpan_persen' => 'float',
    ];
}

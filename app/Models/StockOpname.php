<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpname extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_opnames';
    
    protected $fillable = [
        'no_opname',
        'tanggal',
        'periode',
        'dibuat_oleh',
        'disetujui_oleh',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
    ];

    /**
     * Relasi ke User yang membuat dokumen (Gudang/Admin)
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Relasi ke User yang menyetujui dokumen (Owner)
     */
    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi ke tabel detail (Rincian barang yang di-opname)
     */
    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class, 'stock_opname_id');
    }
}
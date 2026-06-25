<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpnameDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_opname_details';
    
    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'harga_pokok_snapshot',
        'nilai_selisih',
        'keterangan',
    ];

    protected $casts = [
        'stok_sistem' => 'integer',
        'stok_fisik' => 'integer',
        'selisih' => 'integer',
        'harga_pokok_snapshot' => 'integer',
        'nilai_selisih' => 'integer',
    ];

    /**
     * Relasi balik ke dokumen utama (Header)
     */
    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    /**
     * Relasi ke Master Barang (Product / Persediaan)
     * Pastikan class Product ini sesuai dengan nama model barang di aplikasimu
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
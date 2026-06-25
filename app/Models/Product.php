<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'kategori_id',
        'sku',
        'barcode',
        'nama_barang',
        'stok',
        'min_stok',
        'harga_beli',
        'harga_jual',
        'satuan',
        'lead_time_hari',
        'tipe_safety_stock',
        'safety_stock',
        'reorder_point',
        'eoq',
    ];

    protected $casts = [
        'stok' => 'integer',
        'min_stok' => 'integer',
        'harga_beli' => 'integer',
        'harga_jual' => 'integer',
        'safety_stock' => 'integer',
        'reorder_point' => 'integer',
        'eoq' => 'integer',
        'lead_time_hari' => 'integer',
    ];

    // Relasi: Satu produk memiliki satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi: Satu produk bisa memiliki banyak riwayat transaksi (Barang Masuk/Keluar)
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'product_id');
    }
}
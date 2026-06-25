<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model
{
    use HasFactory, SoftDeletes;

    // 1. Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
        'qty_diterima',
        'qty_rusak',
        'harga_satuan',
        'diskon',
        'subtotal',
        'spesifikasi',
        'tgl_expired',
    ];

    protected $casts = [
        'qty' => 'integer',
        'qty_diterima' => 'integer',
        'qty_rusak' => 'integer',
        'harga_satuan' => 'decimal:2',
        'diskon' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tgl_expired' => 'date:Y-m-d',
    ];

    /**
     * 2. Relasi ke Header Transaksi (Kop Surat/Nota)
     * Fungsi ini memberitahu Laravel bahwa setiap baris keranjang 
     * pasti dimiliki oleh 1 Nota Transaksi utama.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * 3. Relasi ke Master Data Produk (Persediaan)
     * Fungsi ini menghubungkan baris keranjang dengan profil barang aslinya.
     * Ini sangat berguna di sistem Laporan untuk mengambil nama barang dan kategorinya.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
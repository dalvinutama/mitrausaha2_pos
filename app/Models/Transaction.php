<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'no_transaksi', 'jenis_transaksi', 'tanggal', 
        'supplier_id', 'user_id', 'no_referensi', 'tujuan', 
        'kategori_keluar', 'estimasi_datang', 'tipe_pembayaran', 
        'status_pembayaran',
        'info_pengiriman', 'total_nilai', 'catatan', 'status', 'po_id', 'diskon'
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'estimasi_datang' => 'date:Y-m-d',
        'total_nilai' => 'decimal:2',
        'diskon' => 'decimal:2',
    ];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relasi ke User (Siapa yang input data)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Detail Barang (1 Transaksi punya Banyak Item)
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    // Relasi ke Pembayaran (Cicilan/DP)
    public function payments()
    {
        return $this->hasMany(TransactionPayment::class, 'transaction_id');
    }
}
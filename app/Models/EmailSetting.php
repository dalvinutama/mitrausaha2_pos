<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo', 'nama_toko', 'primary_color', 'header_color', 'footer_text',
        'low_stock_title', 'low_stock_intro', 'low_stock_outro', 'low_stock_btn',
        'po_new_title', 'po_new_intro', 'po_new_outro', 'po_new_btn',
        'po_digest_title', 'po_digest_intro',
        'sys_notif_title', 'sys_notif_intro'
    ];

    /**
     * Helper untuk mengambil data setting pertama (karena hanya ada 1 baris)
     */
    public static function getSettings()
    {
        return self::first();
    }
}

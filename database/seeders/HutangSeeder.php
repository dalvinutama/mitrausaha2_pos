<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Models\User;

class HutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier = Supplier::first();
        $admin = User::where('role', 'admin')->first() ?? User::first();

        if (!$supplier) {
            $supplier = Supplier::create([
                'nama_supplier' => 'PT Makmur Jaya (Dummy)',
                'kontak' => '0812345678',
                'alamat' => 'Jakarta',
                'status' => 'Aktif'
            ]);
        }

        // Dummy Hutang 1
        Transaction::create([
            'no_transaksi' => 'BM-20260621-9901',
            'jenis_transaksi' => 'masuk',
            'tanggal' => date('Y-m-d', strtotime('-10 days')),
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
            'no_referensi' => 'INV/DUMMY/01',
            'catatan' => '[Pembayaran TEMPO. Jatuh Tempo: ' . date('d/m/Y', strtotime('+4 days')) . ']',
            'status' => 'selesai',
            'tipe_pembayaran' => 'tempo',
            'status_pembayaran' => 'belum_lunas',
            'total_nilai' => 5000000,
        ]);

        // Dummy Hutang 2 (Lewat Jatuh Tempo)
        Transaction::create([
            'no_transaksi' => 'BM-20260621-9902',
            'jenis_transaksi' => 'masuk',
            'tanggal' => date('Y-m-d', strtotime('-20 days')),
            'supplier_id' => $supplier->id,
            'user_id' => $admin->id,
            'no_referensi' => 'INV/DUMMY/02',
            'catatan' => '[Pembayaran TEMPO. Jatuh Tempo: ' . date('d/m/Y', strtotime('-6 days')) . ']',
            'status' => 'selesai',
            'tipe_pembayaran' => 'tempo',
            'status_pembayaran' => 'belum_lunas',
            'total_nilai' => 12500000,
        ]);
    }
}

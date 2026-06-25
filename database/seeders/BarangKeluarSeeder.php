<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BarangKeluarSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            $userId = 3; // Dika Afgan (role: penjualan)

            $transactions = [
                // ========================
                // A. INDIVIDU (13 transaksi)
                // ========================

                // 1. Budi Hartono - 01 Juni 2026
                [
                    'tanggal' => '2026-06-01', 'tujuan' => 'Budi Hartono',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 1, 'qty' => 5, 'harga' => 75000],
                    ],
                ],
                // 2. Maya Sari - 01 Juni 2026
                [
                    'tanggal' => '2026-06-01', 'tujuan' => 'Maya Sari',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 1, 'qty' => 3, 'harga' => 75000],
                        ['product_id' => 23, 'qty' => 5, 'harga' => 54000],
                    ],
                ],
                // 3. Dodi Firmansyah - 02 Juni 2026
                [
                    'tanggal' => '2026-06-02', 'tujuan' => 'Dodi Firmansyah',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 11, 'qty' => 4, 'harga' => 50000],
                        ['product_id' => 26, 'qty' => 2, 'harga' => 18000],
                    ],
                ],
                // 4. Rina Wijaya - 03 Juni 2026
                [
                    'tanggal' => '2026-06-03', 'tujuan' => 'Rina Wijaya',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 15, 'qty' => 2, 'harga' => 135000],
                        ['product_id' => 17, 'qty' => 1, 'harga' => 220000],
                    ],
                ],
                // 5. Agus Salim - 04 Juni 2026
                [
                    'tanggal' => '2026-06-04', 'tujuan' => 'Agus Salim',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 19, 'qty' => 3, 'harga' => 24000],
                        ['product_id' => 22, 'qty' => 5, 'harga' => 12000],
                    ],
                ],
                // 6. Yanti Kusuma - 05 Juni 2026
                [
                    'tanggal' => '2026-06-05', 'tujuan' => 'Yanti Kusuma',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 1, 'qty' => 8, 'harga' => 75000],
                        ['product_id' => 5, 'qty' => 10, 'harga' => 50000],
                    ],
                ],
                // 7. Irwan Setiawan - 06 Juni 2026
                [
                    'tanggal' => '2026-06-06', 'tujuan' => 'Irwan Setiawan',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 13, 'qty' => 3, 'harga' => 20000],
                        ['product_id' => 11, 'qty' => 2, 'harga' => 50000],
                        ['product_id' => 26, 'qty' => 1, 'harga' => 18000],
                    ],
                ],
                // 8. Dewi Sartika - 07 Juni 2026
                [
                    'tanggal' => '2026-06-07', 'tujuan' => 'Dewi Sartika',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 1, 'qty' => 3, 'harga' => 75000],
                        ['product_id' => 19, 'qty' => 5, 'harga' => 24000],
                    ],
                ],
                // 9. Fajar Prasetyo - 08 Juni 2026
                [
                    'tanggal' => '2026-06-08', 'tujuan' => 'Fajar Prasetyo',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 23, 'qty' => 3, 'harga' => 54000],
                        ['product_id' => 3, 'qty' => 2, 'harga' => 95000],
                    ],
                ],
                // 10. Hj. Fatimah - 09 Juni 2026
                [
                    'tanggal' => '2026-06-09', 'tujuan' => 'Hj. Fatimah',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 1, 'qty' => 10, 'harga' => 75000],
                        ['product_id' => 23, 'qty' => 10, 'harga' => 54000],
                        ['product_id' => 15, 'qty' => 3, 'harga' => 135000],
                        ['product_id' => 13, 'qty' => 5, 'harga' => 20000],
                    ],
                ],
                // 11. Tono Hartono - 10 Juni 2026
                [
                    'tanggal' => '2026-06-10', 'tujuan' => 'Tono Hartono',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 13, 'qty' => 2, 'harga' => 20000],
                        ['product_id' => 11, 'qty' => 3, 'harga' => 50000],
                        ['product_id' => 17, 'qty' => 1, 'harga' => 220000],
                    ],
                ],
                // 12. Sari Dewi - 11 Juni 2026
                [
                    'tanggal' => '2026-06-11', 'tujuan' => 'Sari Dewi',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 5, 'qty' => 5, 'harga' => 50000],
                        ['product_id' => 15, 'qty' => 1, 'harga' => 135000],
                    ],
                ],
                // 13. Gunawan Saputra - 12 Juni 2026
                [
                    'tanggal' => '2026-06-12', 'tujuan' => 'Gunawan Saputra',
                    'kategori' => 'PENJUALAN', 'items' => [
                        ['product_id' => 13, 'qty' => 3, 'harga' => 20000],
                        ['product_id' => 15, 'qty' => 1, 'harga' => 135000],
                        ['product_id' => 19, 'qty' => 3, 'harga' => 24000],
                    ],
                ],

                // ========================
                // B. PROYEK / MANDOR (9 transaksi)
                // ========================

                // 14. Ny. Ratna - 01 Juni 2026 (no_spk: SPK-2026-001)
                [
                    'tanggal' => '2026-06-01', 'tujuan' => 'Ny. Ratna - Rumah Pribadi',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-001',
                    'catatan' => 'Mandor: Pak Jumadi',
                    'items' => [
                        ['product_id' => 1, 'qty' => 30, 'harga' => 75000],
                        ['product_id' => 6, 'qty' => 40, 'harga' => 82000],
                        ['product_id' => 13, 'qty' => 10, 'harga' => 20000],
                        ['product_id' => 19, 'qty' => 10, 'harga' => 24000],
                    ],
                ],
                // 15. Bpk. Ahmad - 02 Juni 2026 (no_spk: SPK-2026-002)
                [
                    'tanggal' => '2026-06-02', 'tujuan' => 'Bpk. Ahmad - Rumah Pribadi',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-002',
                    'catatan' => 'Mandor: Pak Sulaiman',
                    'items' => [
                        ['product_id' => 1, 'qty' => 25, 'harga' => 75000],
                        ['product_id' => 5, 'qty' => 30, 'harga' => 50000],
                        ['product_id' => 13, 'qty' => 15, 'harga' => 20000],
                        ['product_id' => 11, 'qty' => 10, 'harga' => 50000],
                    ],
                ],
                // 16. Bpk. Edi - 03 Juni 2026 (no_spk: SPK-2026-003)
                [
                    'tanggal' => '2026-06-03', 'tujuan' => 'Bpk. Edi - Rumah Pribadi',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-003',
                    'catatan' => 'Mandor: Pak Rusdi',
                    'items' => [
                        ['product_id' => 1, 'qty' => 20, 'harga' => 75000],
                        ['product_id' => 23, 'qty' => 30, 'harga' => 54000],
                        ['product_id' => 19, 'qty' => 8, 'harga' => 24000],
                        ['product_id' => 26, 'qty' => 10, 'harga' => 18000],
                    ],
                ],
                // 17. Bpk. Herman - 04 Juni 2026 (Ruko 2 Lantai - SPK-2026-004)
                [
                    'tanggal' => '2026-06-04', 'tujuan' => 'Bpk. Herman - Ruko 2 Lantai',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-004',
                    'catatan' => 'Mandor: Pak Taufik',
                    'items' => [
                        ['product_id' => 1, 'qty' => 40, 'harga' => 75000],
                        ['product_id' => 6, 'qty' => 60, 'harga' => 82000],
                        ['product_id' => 5, 'qty' => 25, 'harga' => 50000],
                        ['product_id' => 13, 'qty' => 20, 'harga' => 20000],
                        ['product_id' => 11, 'qty' => 15, 'harga' => 50000],
                        ['product_id' => 19, 'qty' => 10, 'harga' => 24000],
                        ['product_id' => 26, 'qty' => 10, 'harga' => 18000],
                    ],
                ],
                // 18. Bpk. Yosep - 05 Juni 2026 (no_spk: SPK-2026-005)
                [
                    'tanggal' => '2026-06-05', 'tujuan' => 'Bpk. Yosep - Rumah Pribadi',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-005',
                    'catatan' => 'Mandor: Pak Kariman',
                    'items' => [
                        ['product_id' => 15, 'qty' => 8, 'harga' => 135000],
                        ['product_id' => 17, 'qty' => 1, 'harga' => 220000],
                        ['product_id' => 23, 'qty' => 20, 'harga' => 54000],
                        ['product_id' => 1, 'qty' => 15, 'harga' => 75000],
                    ],
                ],
                // 19. Bpk. Daryono - 07 Juni 2026 (no_spk: SPK-2026-006)
                [
                    'tanggal' => '2026-06-07', 'tujuan' => 'Bpk. Daryono - Rumah Pribadi',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-006',
                    'catatan' => 'Mandor: Pak Marsono',
                    'items' => [
                        ['product_id' => 1, 'qty' => 20, 'harga' => 75000],
                        ['product_id' => 5, 'qty' => 30, 'harga' => 50000],
                        ['product_id' => 23, 'qty' => 20, 'harga' => 54000],
                        ['product_id' => 19, 'qty' => 5, 'harga' => 24000],
                    ],
                ],
                // 20. Bpk. Suhartono - 08 Juni 2026 (no_spk: SPK-2026-007)
                [
                    'tanggal' => '2026-06-08', 'tujuan' => 'Bpk. Suhartono - Rumah Baru',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-007',
                    'catatan' => 'Mandor: Pak Gufron',
                    'items' => [
                        ['product_id' => 1, 'qty' => 30, 'harga' => 75000],
                        ['product_id' => 6, 'qty' => 50, 'harga' => 82000],
                        ['product_id' => 13, 'qty' => 20, 'harga' => 20000],
                        ['product_id' => 11, 'qty' => 10, 'harga' => 50000],
                        ['product_id' => 26, 'qty' => 5, 'harga' => 18000],
                    ],
                ],
                // 21. Ny. Sumiati - 10 Juni 2026 (no_spk: SPK-2026-008)
                [
                    'tanggal' => '2026-06-10', 'tujuan' => 'Ny. Sumiati - Rumah Pribadi',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-008',
                    'catatan' => 'Mandor: Pak Sobirin',
                    'items' => [
                        ['product_id' => 23, 'qty' => 20, 'harga' => 54000],
                        ['product_id' => 1, 'qty' => 15, 'harga' => 75000],
                        ['product_id' => 15, 'qty' => 5, 'harga' => 135000],
                    ],
                ],
                // 22. Bpk. Zainal - 12 Juni 2026 (no_spk: SPK-2026-009)
                [
                    'tanggal' => '2026-06-12', 'tujuan' => 'Bpk. Zainal - Renovasi Total',
                    'kategori' => 'PROYEK', 'no_ref' => 'SPK-2026-009',
                    'catatan' => 'Mandor: Pak Ridwan',
                    'items' => [
                        ['product_id' => 1, 'qty' => 25, 'harga' => 75000],
                        ['product_id' => 5, 'qty' => 20, 'harga' => 50000],
                        ['product_id' => 23, 'qty' => 10, 'harga' => 54000],
                        ['product_id' => 13, 'qty' => 8, 'harga' => 20000],
                        ['product_id' => 15, 'qty' => 3, 'harga' => 135000],
                        ['product_id' => 19, 'qty' => 5, 'harga' => 24000],
                        ['product_id' => 17, 'qty' => 2, 'harga' => 220000],
                    ],
                ],
            ];

            $counter = 100;
            foreach ($transactions as $data) {
                $counter++;
                $noTransaksi = 'BK-' . date('Ymd', strtotime($data['tanggal'])) . '-' . $counter;

                // Hitung total
                $total = collect($data['items'])->sum(fn($i) => $i['qty'] * $i['harga']);

                // Buat transaksi
                $transaksi = Transaction::create([
                    'no_transaksi'    => $noTransaksi,
                    'jenis_transaksi' => 'keluar',
                    'tanggal'         => $data['tanggal'],
                    'kategori_keluar' => $data['kategori'],
                    'tujuan'          => $data['tujuan'],
                    'no_referensi'    => $data['no_ref'] ?? null,
                    'catatan'         => $data['catatan'] ?? null,
                    'user_id'         => $userId,
                    'total_nilai'     => $total,
                    'status'          => 'selesai',
                ]);

                // Buat item & kurangi stok
                foreach ($data['items'] as $item) {
                    $subtotal = $item['qty'] * $item['harga'];

                    TransactionItem::create([
                        'transaction_id' => $transaksi->id,
                        'product_id'     => $item['product_id'],
                        'qty'            => $item['qty'],
                        'harga_satuan'   => $item['harga'],
                        'subtotal'       => $subtotal,
                    ]);

                    // Kurangi stok
                    Product::where('id', $item['product_id'])
                        ->decrement('stok', $item['qty']);
                }

                $this->command->info("OK: {$noTransaksi} - {$data['tujuan']} (Rp" . number_format($total, 0, ',', '.') . ")");
            }

            DB::commit();
            $this->command->info('=== SELESAI: 22 transaksi Barang Keluar berhasil diinput ===');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('GAGAL: ' . $e->getMessage());
        }
    }
}

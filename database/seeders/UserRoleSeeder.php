<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Bapak Owner',
                'email' => 'owner@mitrausaha2.com',
                'password' => Hash::make('password123'),
                'role' => 'owner',
            ],
            [
                'name' => 'Admin Keuangan',
                'email' => 'admin@mitrausaha2.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staf Penjualan',
                'email' => 'penjualan@mitrausaha2.com',
                'password' => Hash::make('password123'),
                'role' => 'penjualan',
            ],
            [
                'name' => 'Kepala Gudang',
                'email' => 'gudang@mitrausaha2.com',
                'password' => Hash::make('password123'),
                'role' => 'gudang',
            ],
            [
                'name' => 'Kurir Pengiriman',
                'email' => 'pengiriman@mitrausaha2.com',
                'password' => Hash::make('password123'),
                'role' => 'pengiriman',
            ],
            [
                'name' => 'Mbak Kasir',
                'email' => 'kasir@mitrausaha2.com',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
            ],
        ];

        foreach ($users as $user) {
            // Gunakan updateOrCreate agar tidak error / dobel kalau dijalankan berkali-kali
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Memanggil seeder akun secara otomatis
        $this->call([
            UserRoleSeeder::class,
        ]);
    }
}
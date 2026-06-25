<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_supplier' => $this->faker->company(),
            'alamat' => $this->faker->address(),
            'nama_pic' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'kategori_suplai' => $this->faker->word(),
            'termin_default' => '30 hari',
            'nama_bank' => $this->faker->word(),
            'no_rekening' => $this->faker->bankAccountNumber(),
            'status' => 'aktif',
        ];
    }
}

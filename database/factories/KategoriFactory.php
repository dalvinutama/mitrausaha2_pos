<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->word(),
            'prefix_sku' => strtoupper($this->faker->lexify('???')),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kategori_id' => \App\Models\Kategori::factory(),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'nama_barang' => $this->faker->word(),
            'stok' => $this->faker->numberBetween(0, 100),
            'harga_beli' => $this->faker->numberBetween(1000, 100000),
            'harga_jual' => $this->faker->numberBetween(2000, 150000),
            'satuan' => 'Unit',
            'lead_time_hari' => $this->faker->numberBetween(1, 7),
            'tipe_safety_stock' => 'manual',
            'safety_stock' => $this->faker->numberBetween(0, 20),
            'reorder_point' => 0,
            'eoq' => 0,
        ];
    }
}

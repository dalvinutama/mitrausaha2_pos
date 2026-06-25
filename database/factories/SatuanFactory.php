<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SatuanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_satuan' => $this->faker->unique()->word(),
        ];
    }
}

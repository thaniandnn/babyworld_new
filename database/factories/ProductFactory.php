<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_produk' => $this->faker->word(),
            'kategori' => $this->faker->randomElement(['Pakaian', 'Perlengkapan', 'Makanan Bayi', 'Mainan', 'Popok']),
            'deskripsi' => $this->faker->sentence(10),
            'harga' => $this->faker->numberBetween(10000, 500000),
            'foto' => $this->faker->imageUrl(400, 400, 'product'),
        ];
    }
}

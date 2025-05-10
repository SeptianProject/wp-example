<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HouseFactory extends Factory
{
    protected $model = \App\Models\House::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company . ' Residence',
            'lokasi' => $this->faker->city,
            'harga' => $this->faker->numberBetween(500000000, 1500000000),
            'luas_tanah' => $this->faker->numberBetween(60, 200),
            'luas_bangunan' => $this->faker->numberBetween(36, 150),
            'fasilitas' => implode(', ', $this->faker->randomElements([
                'Taman',
                'Security 24 jam',
                'Kolam Renang',
                'Playground',
                'Jogging Track',
                'Mushola'
            ], 3)),
            'akses_transportasi' => $this->faker->randomElement(['Dekat Jalan Raya', 'Akses Mudah', 'Transportasi Umum']),
            'jarak_tempuh' => $this->faker->randomFloat(2, 1, 10), // dalam km
        ];
    }
}

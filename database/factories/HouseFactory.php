<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class HouseFactory extends Factory
{
    protected $model = \App\Models\House::class;

    public function definition(): array
    {
        $fasilitasList = [
            'Tempat Ibadah',
            'Taman Bermain',
            'Parkir Luas',
            'Keamanan 24 Jam',
            'Akses WiFi',
        ];

        return [
            'nama' => $this->faker->company . ' Residence',
            'lokasi' => $this->faker->city,
            'harga' => $this->faker->numberBetween(5000000, 15000000),
            'luas_tanah' => $this->faker->numberBetween(60, 200),
            'luas_bangunan' => $this->faker->numberBetween(36, 150),
            'fasilitas' => Arr::random($fasilitasList, rand(1, 3)),
            'akses_transportasi' => $this->faker->randomElement(['Dekat Jalan Raya', 'Akses Mudah', 'Transportasi Umum']),
            'jarak_tempuh' => $this->faker->randomFloat(2, 1, 10), // dalam km
        ];
    }
}

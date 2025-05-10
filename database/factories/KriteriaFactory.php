<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KriteriaFactory extends Factory
{
    protected $model = \App\Models\Kriteria::class;

    public function definition(): array
    {
        return [
            'nama' => 'Nama Kriteria Dummy',
            'kode' => 'X',
            'bobot' => 0.0,
        ];
    }
}

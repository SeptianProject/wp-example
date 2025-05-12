<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class HouseFactory extends Factory
{
    protected $model = \App\Models\House::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company . ' Residence',
        ];
    }
}

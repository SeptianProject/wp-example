<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HouseKriteriaScoreSeeder extends Seeder
{
    public function run(): void
    {
        $houses = \App\Models\House::all();
        $kriterias = \App\Models\Kriteria::all();

        foreach ($houses as $house) {
            foreach ($kriterias as $kriteria) {
                \App\Models\HouseKriteriaScore::create([ 
                    'house_id' => $house->id,
                    'kriteria_id' => $kriteria->id,
                    'nilai' => match ($kriteria->code) {
                        'C' => $house->harga, // Harga Cicilan
                        'L' => rand(1, 10),   // Lokasi (semakin strategis semakin tinggi)
                        'LT' => $house->luas_tanah,
                        'LB' => $house->luas_bangunan,
                        'F' => rand(1, 5),     // Skor fasilitas (jumlah/kelengkapan)
                        'AT' => rand(1, 10),   // Akses transportasi (1-10)
                        'JTK' => $house->jarak_tempuh,
                        default => rand(1, 10)
                    }
                ]);
            }
        }
    }
}

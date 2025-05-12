<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            [
                'nama' => 'Harga Cicilan',
                'kode' => 'C',
                'bobot' => 0.3,
                'type' => 'cost',
                // 'field_type' => 'number',
            ],
            [
                'nama' => 'Lokasi',
                'kode' => 'L',
                'bobot' => 0.2,
                'type' => 'benefit',
                // 'field_type' => 'text',
            ],
            [
                'nama' => 'Luas Tanah',
                'kode' => 'LT',
                'bobot' => 0.15,
                'type' => 'benefit',
                // 'field_type' => 'number',
            ],
            [
                'nama' => 'Luas Bangunan',
                'kode' => 'LB',
                'bobot' => 0.15,
                'type' => 'benefit',
                // 'field_type' => 'number',
            ],
            [
                'nama' => 'Fasilitas',
                'kode' => 'F',
                'bobot' => 0.10,
                'type' => 'benefit',
                // 'field_type' => 'tags',
            ],
            [
                'nama' => 'Akses Transportasi',
                'kode' => 'AT',
                'bobot' => 0.05,
                'type' => 'benefit',
                // 'field_type' => 'text',
            ],
            [
                'nama' => 'Jarak dari Tempat Kerja',
                'kode' => 'JTK',
                'bobot' => 0.05,
                'type' => 'cost',
                // 'field_type' => 'number',
            ],
        ];

        foreach ($criteria as $criterion) {
            Kriteria::create($criterion);
        }
    }
}

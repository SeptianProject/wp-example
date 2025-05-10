<?php

namespace Database\Seeders;

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
            ['nama' => 'Harga Cicilan', 'kode' => 'C', 'bobot' => 0.3],
            ['nama' => 'Lokasi', 'kode' => 'L', 'bobot' => 0.2],
            ['nama' => 'Luas Tanah', 'kode' => 'LT', 'bobot' => 0.15],
            ['nama' => 'Luas Bangunan', 'kode' => 'LB', 'bobot' => 0.15],
            ['nama' => 'Fasilitas', 'kode' => 'F', 'bobot' => 0.1],
            ['nama' => 'Akses Transportasi', 'kode' => 'AT', 'bobot' => 0.05],
            ['nama' => 'Jarak dari Tempat Kerja', 'kode' => 'JTK', 'bobot' => 0.05],
        ];

        foreach ($criteria as $item) {
            \App\Models\Kriteria::create($item);
        }
    }
}

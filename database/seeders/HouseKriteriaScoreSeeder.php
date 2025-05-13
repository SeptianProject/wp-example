<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HouseKriteriaScoreSeeder extends Seeder
{
    public function run(): void
    {
        $houses = \App\Models\House::all();
        $criteria = \App\Models\Kriteria::all();

        foreach ($houses as $house) {
            foreach ($criteria as $criterion) {
                $nilai = $this->generateValueForCriterion($criterion->kode);
                $keterangan = $this->generateDescriptionForCriterion($criterion->kode, $nilai, $criterion->nama);

                $house->kriteriaScores()->create([
                    'kriteria_id' => $criterion->id,
                    'nilai' => $nilai,
                    'keterangan' => $keterangan,
                ]);
            }
        }
    }

    private function generateValueForCriterion($kode)
    {
        switch ($kode) {
            case 'C': // Harga Cicilan
                return rand(10000000, 100000000); // 10jt - 100jt
            case 'L': // Lokasi
                return rand(1, 5); // 1-5 rating
            case 'LT': // Luas Tanah
                return rand(70, 500); // 70-500 m²
            case 'LB': // Luas Bangunan
                return rand(45, 400); // 45-400 m²
            case 'F': // Fasilitas
                return rand(2, 6); // selalu 3 fasilitas sesuai permintaan
            case 'AT': // Akses Transportasi
                return rand(1, 5); // 1-5 rating
            case 'JTK': // Jarak dari Tempat Kerja
                return rand(1, 30); // 1-30 km
            default:
                return rand(1, 10);
        }
    }

    private function generateDescriptionForCriterion($kode, $nilai, $nama)
    {
        switch ($kode) {
            case 'C': // Harga Cicilan
                return 'Cicilan sebesar Rp. ' . number_format($nilai, 0, ',', '.') . ' per bulan';
            case 'L': // Lokasi
                $locations = ['Pusat Kota', 'Pinggiran Kota', 'Area Perumahan Elite', 'Dekat Kampus', 'Area Komersial'];
                return 'Lokasi di area ' . $locations[array_rand($locations)] . ' dengan rating ' . $nilai;
            case 'LT': // Luas Tanah
                return 'Luas tanah ' . $nilai . ' m²';
            case 'LB': // Luas Bangunan
                return 'Luas bangunan ' . $nilai . ' m²';
            case 'F': // Fasilitas
                $allFacilities = [
                    'Kolam Renang',
                    'Taman',
                    'Garasi',
                    'Carport',
                    'Keamanan 24 Jam',
                    'Taman Bermain',
                    'Gym',
                    'Mini Market',
                    'CCTV',
                    'Ruang Tamu',
                    'Dapur'
                ];
                shuffle($allFacilities);
                $selectedFacilities = array_slice($allFacilities, 0, 3);
                return implode(', ', $selectedFacilities);
            case 'AT': // Akses Transportasi
                $transports = ['Bus', 'Kereta', 'Angkutan Umum', 'Ojek Online'];
                $selectedCount = rand(1, 3);
                shuffle($transports);
                $selectedTransports = array_slice($transports, 0, $selectedCount);
                return 'Akses ke ' . implode(', ', $selectedTransports) . ' dengan rating ' . $nilai;
            case 'JTK': // Jarak dari Tempat Kerja
                return 'Jarak ' . $nilai . ' km dari tempat kerja';
            default:
                return 'Deskripsi untuk kriteria ' . $nama . ' dengan nilai ' . $nilai;
        }
    }
}

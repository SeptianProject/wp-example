<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Kriteria;
use App\Models\HouseKriteriaScore;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseRecommendationController extends Controller
{
     public function index()
     {
          $houses = House::all();
          return view('dashboard', compact('houses'));
     }

     public function compareHouses(Request $request)
     {
          $validated = $request->validate([
               'house1' => 'required|exists:houses,id',
               'house2' => 'required|exists:houses,id',
               'house3' => 'required|exists:houses,id',
          ]);

          $selectedHouses = House::whereIn('id', [
               $validated['house1'],
               $validated['house2'],
               $validated['house3']
          ])->get();

          $wpResults = $this->calculateWeightProduct($selectedHouses);

          // Save the top recommendation to the database
          if (count($wpResults) > 0 && Auth::check()) {
               // Get the house with the highest score (already sorted in calculateWeightProduct)
               $topRecommendation = $wpResults[0];

               // Save to recommendations table
               Recommendation::create([
                    'user_id' => Auth::id(),
                    'house_id' => $topRecommendation['house_id'],
                    'nilai' => $topRecommendation['vector_v'],
               ]);
          }

          return view('detail-house', compact('selectedHouses', 'wpResults'));
     }

     private function calculateWeightProduct($houses)
     {
          $kriterias = Kriteria::all();
          $criteriaMap = [];

          // mapping kriteria ke field
          foreach ($kriterias as $kriteria) {
               $field = $this->mapKriteriaToField($kriteria->kode);
               $type = $kriteria->type ?? ($this->isKriteriaCost($kriteria->kode) ? 'cost' : 'benefit');
               $weight = $type == 'cost' ? -1 * $kriteria->bobot : $kriteria->bobot;

               $criteriaMap[$field] = [
                    'bobot' => $weight,
                    'type' => $type,
                    'kode' => $kriteria->kode
               ];
          }

          $results = [];
          $vectorS = [];
          $sumVectorS = 0;

          // menghitung nilai vektor s
          foreach ($houses as $house) {
               $s = 1;
               foreach ($criteriaMap as $key => $criterion) {
                    $weight = abs($criterion['bobot']);
                    $value = $this->getNumericValue($house, $key, $criterion['kode']);

                    // bernilai negatif karena cost
                    // jika nilai terlalu kecil, gunakan 0.001 untuk menghindari pembagian dengan 0
                    if ($criterion['type'] == 'cost') {
                         $s *= pow(1 / max(0.001, $value), $weight);
                    } else {
                         $s *= pow($value, $weight);
                    }
               }

               $vectorS[$house->id] = $s;
               $sumVectorS += $s;
          }

          // menghitung nilai vektor v
          foreach ($houses as $house) {
               $v = $vectorS[$house->id] / $sumVectorS;

               $results[] = [
                    'house_id' => $house->id,
                    'house_name' => $house->nama,
                    'vector_s' => $vectorS[$house->id],
                    'vector_v' => $v,
                    'percentage' => $v * 100
               ];
          }

          usort($results, function ($a, $b) {
               return $b['vector_v'] <=> $a['vector_v'];
          });

          return $results;
     }

     private function getNumericValue($house, $key, $kode)
     {
          $score = HouseKriteriaScore::where('house_id', $house->id)
               ->whereHas('kriteria', function ($query) use ($kode) {
                    $query->where('kode', $kode);
               })->first();

          if ($score && $score->nilai !== null) {
               return $score->nilai;
          }

          $value = $house->$key;

          if (is_string($value)) {
               if ($key == 'lokasi') {
                    $locationMap = [
                         'Jakarta' => 5,
                         'Bandung' => 4,
                         'Surabaya' => 4,
                         'Yogyakarta' => 3,
                         'Medan' => 3,
                         'default' => 2.5
                    ];

                    return $locationMap[$value] ?? $locationMap['default'];
               }

               if ($key == 'akses_transportasi') {
                    $transportMap = [
                         'Dekat Jalan Raya' => 5,
                         'Akses Mudah' => 4,
                         'Transportasi Umum' => 3,
                         'default' => 2
                    ];

                    return $transportMap[$value] ?? $transportMap['default'];
               }

               return 1;
          }

          return $value;
     }

     private function mapKriteriaToField($kode)
     {
          $mapping = [
               'C' => 'harga',
               'L' => 'lokasi',
               'LT' => 'luas_tanah',
               'LB' => 'luas_bangunan',
               'F' => 'jumlah_fasilitas',
               'AT' => 'akses_transportasi',
               'JTK' => 'jarak_tempuh'
          ];

          return $mapping[$kode] ?? $kode;
     }

     private function isKriteriaCost($kode)
     {
          return in_array($kode, ['C', 'JTK']);
     }

     public function userRecommendations()
     {
          if (!Auth::check()) {
               return redirect()->route('login');
          }

          $recommendations = Recommendation::where('user_id', Auth::id())
               ->with('house')
               ->get();

          return view('recommendations.history', compact('recommendations'));
     }

     public function showRecommendation(Recommendation $recommendation)
     {
          if ($recommendation->user_id !== Auth::id()) {
               abort(403, 'Unauthorized action.');
          }

          $house = $recommendation->house;

          $criterias = Kriteria::all();

          $result = [
               'house_id' => $house->id,
               'house_name' => $house->nama,
               'vector_v' => $recommendation->nilai,
               'percentage' => $recommendation->nilai * 100
          ];

          $kriteriaScores = $house->kriteriaScores()->with('kriteria')->get();

          return view('recommendations.detail', [
               'recommendation' => $recommendation,
               'house' => $house,
               'criterias' => $criterias,
               'kriteriaScores' => $kriteriaScores,
               'result' => $result
          ]);
     }
}

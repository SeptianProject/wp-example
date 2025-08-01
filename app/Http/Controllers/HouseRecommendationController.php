<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Kriteria;
use App\Models\HouseKriteriaScore;
use App\Models\Recommendation;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseRecommendationController extends Controller
{
     public function index()
     {
          $houses = House::all();
          return view('customer.dashboard', compact('houses'));
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

          if (count($wpResults) > 0 && Auth::check()) {
               $topRecommendation = $wpResults[0];

               Recommendation::create([
                    'user_id' => Auth::id(),
                    'house_id' => $topRecommendation['house_id'],
                    'nilai' => $topRecommendation['vector_v'],
               ]);
          }

          return view('customer.houses.calculate', compact('selectedHouses', 'wpResults'));
     }

     private function calculateWeightProduct($houses)
     {
          $kriterias = Kriteria::all();
          $results = [];
          $vectorS = [];
          $sumVectorS = 0;

          // menghitung nilai vektor s
          foreach ($houses as $house) {
               $s = 1;
               foreach ($kriterias as $kriteria) {
                    $type = $kriteria->type;
                    $weight = abs($kriteria->bobot);

                    $score = HouseKriteriaScore::where('house_id', $house->id)
                         ->where('kriteria_id', $kriteria->id)
                         ->first();

                    $value = $score ? $score->nilai : 1;

                    // Gunakan nilai minimum 0,001 untuk nilai 0 untuk menghindari masalah perhitungan
                    if ($type == 'cost') {
                         $s *= pow(1 / max(0.001, $value), $weight);
                    } else {
                         // Untuk kriteria benefit, gunakan setidaknya 0,001 untuk menghindari perkalian dengan 0
                         $s *= pow(max(0.001, $value), $weight);
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

          // sorting data dari tertinggi ke terendah
          usort($results, function ($a, $b) {
               return $b['vector_v'] <=> $a['vector_v'];
          });

          return $results;
     }

     // menampilkan rekomendasi rumah berdasarkan user yang login
     public function userRecommendations()
     {
          if (!Auth::check()) {
               return redirect()->route('login');
          }

          $recommendations = Recommendation::where('user_id', Auth::id())
               ->with('house')
               ->get();

          return view('customer.histories.index', compact('recommendations'));
     }

     // menampilkan detail rekomendasi rumah
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

          return view('customer.histories.detail', [
               'recommendation' => $recommendation,
               'house' => $house,
               'criterias' => $criterias,
               'kriteriaScores' => $kriteriaScores,
               'result' => $result
          ]);
     }

     // menampilkan detail rumah
     public function showHouseDetail($id)
     {
          $house = House::with('kriteriaScores.kriteria')->findOrFail($id);

          return view('customer.houses.detail', compact('house'));
     }

     // mengirim permintaan pertemuan
     public function requestMeeting(Request $request)
     {
          if (!Auth::check()) {
               return redirect()->route('login');
          }

          $meeting = Meeting::create([
               'customer_id' => Auth::id(),
               'date' => null,
               'description' => null,
               'status' => 'requested',
          ]);

          return redirect()->route('dashboard')->with('success', 'Permintaan pertemuan berhasil dikirim. Admin akan menghubungi Anda untuk mengatur jadwal pertemuan.');
     }
}

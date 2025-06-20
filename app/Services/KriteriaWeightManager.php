<?php

namespace App\Services;

use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;

class KriteriaWeightManager
{
     public static function updateWeight(int $kriteriaId, float $newWeight): array
     {
          $kriteria = Kriteria::findOrFail($kriteriaId);

          session()->put('skip_weight_recalculation', true);

          try {
               $kriteria->setNewWeight($newWeight);

               $updatedKriterias = Kriteria::all();
               $totalWeight = $updatedKriterias->sum('bobot');

               return [
                    'success' => true,
                    'message' => "Bobot kriteria {$kriteria->nama} diupdate menjadi {$newWeight}",
                    'total_weight' => $totalWeight,
                    'kriterias' => $updatedKriterias->toArray()
               ];
          } catch (\Exception $e) {
               return [
                    'success' => false,
                    'message' => "Error: " . $e->getMessage()
               ];
          } finally {
               session()->forget('skip_weight_recalculation');
          }
     }

     public static function adjustWeights(array $weights): array
     {
          $totalInputWeight = array_sum($weights);
          if ($totalInputWeight <= 0) {
               return [
                    'success' => false,
                    'message' => 'Total bobot harus lebih dari 0'
               ];
          }

          DB::beginTransaction();

          try {
               session()->put('skip_weight_recalculation', true);

               $normalizedWeights = [];
               foreach ($weights as $id => $weight) {
                    $normalizedWeights[$id] = $weight / $totalInputWeight;
               }

               foreach ($normalizedWeights as $id => $weight) {
                    DB::table('kriterias')
                         ->where('id', $id)
                         ->update(['bobot' => round($weight, 4)]);
               }

               Kriteria::correctRoundingErrors();

               DB::commit();

               $updatedKriterias = Kriteria::all();
               $totalWeight = $updatedKriterias->sum('bobot');

               return [
                    'success' => true,
                    'message' => 'Bobot semua kriteria berhasil diupdate',
                    'total_weight' => $totalWeight,
                    'kriterias' => $updatedKriterias->toArray()
               ];
          } catch (\Exception $e) {
               DB::rollBack();

               return [
                    'success' => false,
                    'message' => "Error: " . $e->getMessage()
               ];
          } finally {
               session()->forget('skip_weight_recalculation');
          }
     }
}

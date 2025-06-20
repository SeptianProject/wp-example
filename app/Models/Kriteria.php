<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
        'bobot',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'bobot' => 'float',
    ];

    public function scores()
    {
        return $this->hasMany(HouseKriteriaScore::class);
    }

    public function isNumeric(): bool
    {
        return $this->field_type === 'number';
    }

    public function isTags(): bool
    {
        return $this->field_type === 'tags';
    }

    public static function redistributeWeights(?int $excludeId = null): void
    {
        session()->put('skip_weight_recalculation', true);

        try {
            DB::transaction(function () use ($excludeId) {
                $query = self::query();
                if ($excludeId) {
                    $query->where('id', '!=', $excludeId);
                }
                $kriterias = $query->get();

                $count = $kriterias->count();
                if ($count === 0) {
                    return;
                }

                $totalWeight = $kriterias->sum('bobot');

                if (abs($totalWeight - 1) < 0.0001) {
                    return;
                }

                foreach ($kriterias as $kriteria) {
                    $normalizedWeight = ($totalWeight > 0)
                        ? round($kriteria->bobot / $totalWeight, 4)
                        : round(1 / $count, 4);

                    DB::table('kriterias')
                        ->where('id', $kriteria->id)
                        ->update(['bobot' => $normalizedWeight]);
                }

                self::correctRoundingErrors();
            });
        } finally {
            session()->forget('skip_weight_recalculation');
        }
    }

    private static function correctRoundingErrors(): void
    {
        $kriterias = self::orderBy('bobot', 'desc')->get();
        $totalWeight = $kriterias->sum('bobot');

        if (abs($totalWeight - 1) > 0.0001) {
            $adjustment = 1 - $totalWeight;
            $largestKriteria = $kriterias->first();

            if ($largestKriteria) {
                $newWeight = $largestKriteria->bobot + $adjustment;
                DB::table('kriterias')
                    ->where('id', $largestKriteria->id)
                    ->update(['bobot' => round($newWeight, 4)]);
            }
        }
    }

    public function setNewWeight(float $newWeight): void
    {
        if ($newWeight <= 0 || $newWeight >= 1) {
            throw new \InvalidArgumentException("Bobot harus antara 0 dan 1");
        }

        DB::transaction(function () use ($newWeight) {
            $otherKriterias = self::where('id', '!=', $this->id)->get();
            $countOthers = $otherKriterias->count();

            if ($countOthers === 0) {
                $this->bobot = 1;
                $this->save();
                return;
            }

            $remainingWeight = 1 - $newWeight;
            $totalOtherWeight = $otherKriterias->sum('bobot');

            $this->bobot = $newWeight;
            $this->save();

            foreach ($otherKriterias as $kriteria) {
                $proportion = ($totalOtherWeight > 0)
                    ? $kriteria->bobot / $totalOtherWeight
                    : 1 / $countOthers;

                $adjustedWeight = round($remainingWeight * $proportion, 4);

                DB::table('kriterias')
                    ->where('id', $kriteria->id)
                    ->update(['bobot' => $adjustedWeight]);
            }

            self::correctRoundingErrors();
        });
    }
}

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
        // 'field_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    /**
     * Redistribute weights to ensure the sum is always 1
     */
    public static function redistributeWeights(): void
    {
        DB::transaction(function () {
            $kriterias = self::all();
            $count = $kriterias->count();

            if ($count === 0) {
                return;
            }

            $totalWeight = $kriterias->sum('bobot');

            // Jika sudah 1, tidak perlu normalisasi
            if (abs($totalWeight - 1) < 0.0001) {
                return;
            }

            // Normalisasi bobot agar totalnya 1
            foreach ($kriterias as $kriteria) {
                // Hindari pemicu observer lagi dengan menggunakan query update
                if ($totalWeight > 0) {
                    // Jika total bobot ada, normalisasi
                    $normalizedWeight = round($kriteria->bobot / $totalWeight, 2);
                } else {
                    // Jika total bobot 0, distribusi sama rata
                    $normalizedWeight = round(1 / $count, 2);
                }

                DB::table('kriterias')
                    ->where('id', $kriteria->id)
                    ->update(['bobot' => $normalizedWeight]);
            }
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
    ];

    public function kriteria()
    {
        return $this->belongsToMany(Kriteria::class, 'house_kriteria_scores')
            ->withPivot('nilai', 'keterangan');
    }

    public function kriteriaScores()
    {
        return $this->hasMany(HouseKriteriaScore::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}

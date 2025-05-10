<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseKriteriaScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'kriteria_id',
        'nilai',
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}

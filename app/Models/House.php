<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'lokasi',
        'harga',
        'luas_tanah',
        'luas_bangunan',
        'fasilitas',
        'akses_transportasi',
        'jarak_tempuh',
    ];

    public function kriteriaScores()
    {
        return $this->hasMany(HouseKriteriaScore::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}

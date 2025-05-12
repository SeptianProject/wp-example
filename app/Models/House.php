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

    protected $casts = [
        'fasilitas' => 'array',
    ];

    public function kriteriaScores()
    {
        return $this->hasMany(HouseKriteriaScore::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }

    public function getJumlahFasilitasAttribute(): int
    {
        return is_array($this->fasilitas) ? count($this->fasilitas) : 0;
    }
}

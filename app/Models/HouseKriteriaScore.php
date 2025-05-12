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
        'keterangan',
    ];

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function getJumlahFasilitasAttribute(): int
    {
        return is_array($this->fasilitas) ? count($this->fasilitas) : 0;
    }

    public function getFormattedNilaiAttribute()
    {
        if ($this->kriteria?->field_type === 'tags' && is_array($this->nilai)) {
            return implode(', ', $this->nilai);
        }

        return $this->nilai;
    }
}

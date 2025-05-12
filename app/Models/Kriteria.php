<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'date',
        'description',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}

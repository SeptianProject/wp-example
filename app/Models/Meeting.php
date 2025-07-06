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
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function isRequested()
    {
        return $this->status === 'requested';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }
}

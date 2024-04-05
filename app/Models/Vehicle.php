<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['marca', 'userId','modelo','image', 'type', 'passenger_capacity', 'luggage_capacity', 'placa','color'];

    public function users()
    {
        return $this->belongsTo(User::class,'userId');
    }
}

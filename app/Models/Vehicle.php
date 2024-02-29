<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['marca', 'modelo', 'year', 'type', 'passenger_capacity', 'luggage_capacity', 'cost_per_day'];
}

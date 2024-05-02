<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Represent extends Model
{
    use HasFactory;

    protected $fillable = ['userId','vehicleId','reservationId','choferId' ];

    public function users()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,'vehicleId');
    }


    public function reservations()
    {
        return $this->belongsTo(Reservation::class, 'reservationId', 'id');
    }

    // public function reservationsRepresent()
    // {
    //     return $this->belongsTo(User::class,'userId');
    // }

}

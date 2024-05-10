<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferz extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'driver_link',
        'journey_code',
        'pickup_date',
        'flight_number',
        'ship_name',
        'train_number',
        'from_location',
        'to_location',
        'flight_number',
        'travellers',
        'suitcases',
        'meet_greet',
        'Add_ons',
        'comments',
        'vehicle_category',
        'partner_reference',
        'status',
        'userId',
        'vehicleId',
        
     ];
   
    public function users()
    {
        return $this->belongsTo(User::class,'userId');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,'vehicleId');
    }


    // public function reservations()
    // {
    //     return $this->belongsTo(Reservation::class, 'reservationId', 'id');
    // }

    // public function reservationsRepresent()
    // {
    //     return $this->belongsTo(User::class,'userId');
    // }

}

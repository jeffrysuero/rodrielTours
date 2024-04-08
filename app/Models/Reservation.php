<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['clientId', 'vehicleId', 'min_KM', 'suitcases', 'total_cost','numPeople','status','url'];

    public function client()
    {
        return $this->belongsTo(Client::class,'clientId');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class,'vehicleId');
    }

    public static function getStatusOptions()
    {
        return ['SIN ASIGNAR' => 'SIN ASIGNAR', 'COMPLETADO' => 'COMPLETADO', 'CREADO' => 'CREADO'];
    }
}

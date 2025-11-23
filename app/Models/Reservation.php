<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'pasajero_id',
        'estado',
        'fecha_reserva',
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function pasajero()
    {
        return $this->belongsTo(User::class, 'pasajero_id');
    }
}

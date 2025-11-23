<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'chofer_id',
        'vehicle_id',
        'titulo',
        'lugar_salida',
        'lugar_llegada',
        'fecha_hora',
        'costo_por_espacio',
        'espacios_totales',
        'espacios_disponibles',
    ];

    // Vehículo asociado
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Usuario chofer (tabla users)
    public function chofer()
    {
        return $this->belongsTo(User::class, 'chofer_id');
    }

    // Reservas asociadas a este ride
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}

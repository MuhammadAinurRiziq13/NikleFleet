<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    protected $table = 'vehicles';
    protected $primaryKey = 'id';
    protected $guarded = [
        'id',
    ];

    public function region()
    {
        return $this->belongsTo(Regions::class);
    }

    public function reservations()
    {
        return $this->hasMany(VehicleReservations::class);
    }
}
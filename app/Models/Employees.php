<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $guarded = [
        'id',
    ];

    public function reservations()
    {
        return $this->hasMany(VehicleReservations::class, 'employee_id');
    }
}
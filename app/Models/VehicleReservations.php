<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleReservations extends Model
{
    protected $table = 'vehicle_reservations';
    protected $primaryKey = 'id';
    protected $guarded = [
        'id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    // Relasi ke tabel vehicles
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id', 'id');
    }

    // Relasi ke user approver_1
    public function approver1()
    {
        return $this->belongsTo(User::class, 'approver_1_id', 'id');
    }

    // Relasi ke user approver_2
    public function approver2()
    {
        return $this->belongsTo(User::class, 'approver_2_id', 'id');
    }

    // Relasi ke tabel mines
    public function mine()
    {
        return $this->belongsTo(Mines::class, 'mine_id', 'id');
    }

    // Relasi ke tabel region
    public function region()
    {
        return $this->belongsTo(Regions::class, 'region_id', 'id');
    }
}
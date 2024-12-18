<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'regions';
    protected $primaryKey = 'id';
    protected $guarded = [
        'id',
    ];

    public function mines()
    {
        return $this->hasMany(Mines::class);
    }
}
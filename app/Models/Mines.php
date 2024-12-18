<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mines extends Model
{
    protected $table = 'mines';
    protected $primaryKey = 'id';
    protected $guarded = [
        'id',
    ];

    public function region()
    {
        return $this->belongsTo(Regions::class);
    }
}
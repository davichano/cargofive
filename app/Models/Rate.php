<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rates';

    protected $fillable = [
        'id',
        'surcharge_id',
        'carrier_id',
        'amount',
        'currency',
        'apply_to',
    ];
}

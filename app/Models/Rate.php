<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rates';

    protected $casts = [
        'apply_to' => 'enum',
    ];

    protected $enums = [
        'apply_to' => ['freight', 'destination', 'origin'],
    ];

    protected $fillable = [
        'id',
        'surcharge_id',
        'carrier_id',
        'amount',
        'currency',
        'apply_to',
    ];
}

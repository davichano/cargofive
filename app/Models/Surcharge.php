<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surcharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'apply_to',
        'calculation_type_id',
        'isGrouped',
        'idFather',
    ];

    /**
     *  Get the calculation type associated with the surcharge.
     */
    public function calculationType()
    {
        return $this->belongsTo(CalculationType::class, 'calculation_type_id', 'id');
    }

    /**
     * Get the rates associated with the surcharge.
     */
    public function rates()
    {
        return $this->hasMany(Rate::class, 'surcharge_id', 'id');
    }

    /**
     * Get the son surcharges associated with the surcharge.
     */
    public function sons()
    {
        return $this->hasMany(Surcharge::class, 'idFather', 'id');
    }
}

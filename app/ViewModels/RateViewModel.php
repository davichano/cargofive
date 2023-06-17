<?php

namespace App\ViewModels;

class RateViewModel
{
    public $id;
    public $surcharge_id;
    public $carrier_id;
    public $amount;
    public $currency;
    public $apply_to;

    public function __construct(array $properties = [])
    {
        $this->id = 0;
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

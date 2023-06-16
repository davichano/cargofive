<?php

namespace App\ViewModels;

class CarrierViewModel
{
    public int $id;
    public string $name;

    public function __construct(array $properties = [])
    {
        $this->id = 0;
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

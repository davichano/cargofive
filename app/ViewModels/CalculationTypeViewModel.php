<?php

namespace App\ViewModels;

class CalculationTypeViewModel
{
    public $id;
    public $name;

    public function __construct(array $properties = [])
    {
        $this->id = 0;
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

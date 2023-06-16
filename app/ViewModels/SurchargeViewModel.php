<?php

namespace App\ViewModels;

/**
 * Class SurchargeViewModel
 *
 * @package App\ViewModels
 */
class SurchargeViewModel
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $apply_to;

    /**
     * @var int
     */
    public $calculation_type_id;

    /**
     * @var bool
     */
    public $isGrouped;

    /**
     * @var int|null
     */
    public $idFather;

    /**
     * @var CalculationTypeViewModel|null
     */
    public $calculation_type;

    /**
     * @var RateViewModel[]
     */
    public $rates;

    /**
     * @var SurchargeViewModel[]
     */
    public $sons;

    /**
     * SurchargeViewModel constructor, when the data is coming from the requests
     * often is coming like an array
     *
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->id = 0;
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }
}

<?php

namespace App\Structure\Services;

use App\Helpers\JJKSort;
use App\Structure\Repositories\SurchargesRepository;
use App\ViewModels\Mappers\SurchargeMapper;

class SurchargesService
{
    protected $surchargesRepository;
    protected $threshold;


    /**
     * @param SurchargesRepository $surchargesRepository
     */
    public function __construct(SurchargesRepository $surchargesRepository)
    {
        $this->threshold = 0.8;
        $this->surchargesRepository = $surchargesRepository;
    }

    public function getAll()
    {
        return SurchargeMapper::listModelToListViewModel($this->surchargesRepository->getAll());
    }

    public function getAllFathers()
    {
        return SurchargeMapper::listModelToListViewModel($this->surchargesRepository->getAllFathers());
    }

    public function group()
    {
        $list = $this->surchargesRepository->getAllUngrouped();
        //Elloquent Collection to Array
        $data = [];
        foreach ($list as $item) $data[] = $item;
        //Grouping items
        $jjksort = new JJKSort();
        $groupedData = $jjksort->groupSimilarSurcharges($data, $this->threshold);
        foreach ($groupedData as $group) {
            $father = $group[0];
            for ($i = 1; $i < count($group); $i++) {
                $group[$i]->idFather = $father->id;
                $this->surchargesRepository->save($group[$i]);
            }
        }
        return $this->getAllFathers();
    }

}

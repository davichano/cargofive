<?php

namespace App\Structure\Services;

use App\Structure\Repositories\CarrierRepository;
use App\ViewModels\CarrierViewModel;
use App\ViewModels\Mappers\CarrierMapper;

class CarrierService
{
    protected CarrierRepository $carrierRepository;

    /**
     * @param CarrierRepository $carrierRepository
     */
    public function __construct(CarrierRepository $carrierRepository)
    {
        $this->carrierRepository = $carrierRepository;
    }

    public function getByName(string $name)
    {
        return CarrierMapper::modelToViewModel($this->carrierRepository->getByName($name));
    }

    public function save(CarrierViewModel $viewModel)
    {
        $model = CarrierMapper::viewModelToModel($viewModel, $this->carrierRepository->getById($viewModel->id));
        return CarrierMapper::modelToViewModel($this->carrierRepository->save($model));
    }
}

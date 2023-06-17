<?php

namespace App\Structure\Concrete\Services;

use App\Structure\Abstract\Repositories\CarrierRepositoryInterface;
use App\Structure\Abstract\Services\CarrierServiceInterface;
use App\ViewModels\CarrierViewModel;
use App\ViewModels\Mappers\CarrierMapper;

class CarrierService implements CarrierServiceInterface
{
    protected CarrierRepositoryInterface $carrierRepository;

    /**
     * @param CarrierRepositoryInterface $carrierRepository
     */
    public function __construct(CarrierRepositoryInterface $carrierRepository)
    {
        $this->carrierRepository = $carrierRepository;
    }

    public function getByName(string $name): CarrierViewModel
    {
        $carrier = $this->carrierRepository->getByName($name);
        if ($carrier->id == 0) {
            $carrier->name = $name;
            $carrier = $this->carrierRepository->save($carrier);
        }
        return CarrierMapper::modelToViewModel($carrier);
    }

    public function save(CarrierViewModel $viewModel): CarrierViewModel
    {
        $model = CarrierMapper::viewModelToModel($viewModel, $this->carrierRepository->getById($viewModel->id));
        return CarrierMapper::modelToViewModel($this->carrierRepository->save($model));
    }
}

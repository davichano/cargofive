<?php

namespace App\Structure\Abstract\Services;

use App\ViewModels\CarrierViewModel;

interface CarrierServiceInterface
{
    public function getByName(string $name): CarrierViewModel;

    public function save(CarrierViewModel $viewModel): CarrierViewModel;
}

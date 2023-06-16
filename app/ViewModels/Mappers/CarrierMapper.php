<?php

namespace App\ViewModels\Mappers;

use App\Models\Carrier;
use App\ViewModels\CarrierViewModel;

/**
 *
 */
class CarrierMapper
{
    /**
     * @param Carrier $model
     * @return CarrierViewModel
     */
    public static function modelToViewModel(Carrier $model): CarrierViewModel
    {
        $viewModel = new CarrierViewModel();
        $viewModel->id = $model->id;
        $viewModel->name = $model->name;
        return $viewModel;
    }

    /**
     * @param CarrierViewModel $viewModel
     * @param Carrier $model
     * @return Carrier
     */
    public static function viewModelToModel(CarrierViewModel $viewModel, Carrier $model): Carrier
    {
        $model->id = $viewModel->id;
        $model->name = $viewModel->name;
        return $model;
    }

    /**
     * @param $list
     * @return array
     */
    public static function listModelToListViewModel($list): array
    {
        $data = [];
        foreach ($list as $item) $data[] = self::modelToViewModel($item);
        return $data;
    }
}

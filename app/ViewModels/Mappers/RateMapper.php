<?php

namespace App\ViewModels\Mappers;

use App\Models\Rate;
use App\ViewModels\RateViewModel;

class RateMapper
{
    public static function modelToViewModel(Rate $model): RateViewModel
    {
        $viewModel = new RateViewModel();
        $viewModel->id = $model->id;
        $viewModel->surcharge_id = $model->surcharge_id;
        $viewModel->carrier_id = $model->carrier_id;
        $viewModel->amount = $model->amount;
        $viewModel->currency = $model->currency;
        $viewModel->apply_to = $model->apply_to;
        return $viewModel;
    }

    public static function viewModelToModel(RateViewModel $viewModel, Rate $model): Rate
    {
        $model->id = $viewModel->id;
        $model->surcharge_id = $viewModel->surcharge_id;
        $model->carrier_id = $viewModel->carrier_id;
        $model->amount = $viewModel->amount;
        $model->currency = $viewModel->currency;
        $model->apply_to = $viewModel->apply_to;
        return $model;
    }

    public static function listModelToListViewModel($list): array
    {
        $data = [];
        foreach ($list as $item) $data[] = self::modelToViewModel($item);

        return $data;
    }
}

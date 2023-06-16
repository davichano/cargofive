<?php

namespace App\ViewModels\Mappers;

use App\Models\CalculationType;
use App\ViewModels\CalculationTypeViewModel;

class CalculationTypeMapper
{
    public static function modelToViewModel(CalculationType $model): CalculationTypeViewModel
    {
        $viewModel = new CalculationTypeViewModel();
        $viewModel->id = $model->id;
        $viewModel->name = $model->name;
        return $viewModel;
    }

    public static function listModelToListViewModel($list): array
    {
        $data = [];
        foreach ($list as $item) $data[] = self::modelToViewModel($item);

        return $data;
    }

}

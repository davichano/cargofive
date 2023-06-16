<?php

namespace App\ViewModels\Mappers;

use App\Models\Surcharge;
use App\ViewModels\CalculationTypeViewModel;
use App\ViewModels\SurchargeViewModel;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SurchargeMapper
 *
 * @package App\ViewModels\Mappers
 */
class SurchargeMapper
{
    /**
     * Convert a Surcharge model to a SurchargeViewModel.
     *
     * @param Surcharge $model
     * @return SurchargeViewModel
     */
    public static function modelToViewModel(Surcharge $model): SurchargeViewModel
    {
        $viewModel = new SurchargeViewModel();
        $viewModel->id = $model->id;
        $viewModel->name = $model->name;
        $viewModel->apply_to = $model->apply_to;
        $viewModel->calculation_type_id = $model->calculation_type_id;
        $viewModel->isGrouped = $model->isGrouped;
        $viewModel->idFather = $model->idFather;
        $viewModel->calculation_type = $model->calculationType != null ? CalculationTypeMapper::modelToViewModel($model->calculationType) : new CalculationTypeViewModel();
        $viewModel->sons = $model->sons != null ? SurchargeMapper::listModelToListViewModel($model->sons) : [];
        return $viewModel;
    }

    /**
     * Convert a SurchargeViewModel to a Surcharge model.
     *
     * @param SurchargeViewModel $viewModel
     * @param Surcharge $model
     * @return Surcharge
     */
    public static function viewModelToModel(SurchargeViewModel $viewModel, Surcharge $model): Surcharge
    {
        $model->id = $viewModel->id;
        $model->name = $viewModel->name;
        $model->apply_to = $viewModel->apply_to;
        $model->calculation_type_id = $viewModel->calculation_type_id;
        $model->isGrouped = $viewModel->isGrouped;
        $model->idFather = $viewModel->idFather;
        return $model;
    }

    /**
     * Convert a list of Surcharge models to a list of SurchargeViewModels.
     *
     * @param Collection $list | Surcharge[]
     * @return array
     */
    public static function listModelToListViewModel($list): array
    {
        $data = [];
        foreach ($list as $item) $data[] = self::modelToViewModel($item);

        return $data;
    }
}

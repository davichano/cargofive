<?php

namespace App\Structure\Abstract\Services;


use App\ViewModels\RateViewModel;

/**
 *
 */
interface RateServiceInterface
{
    /**
     * @param int $id
     * @return RateViewModel
     */
    public function getById(int $id): RateViewModel;

    /**
     * @param RateViewModel $viewModel
     * @return RateViewModel
     */
    public function save(RateViewModel $viewModel): RateViewModel;
}

<?php

namespace App\Structure\Concrete\Services;

use App\Structure\Abstract\Repositories\RateRepositoryInterface;
use App\Structure\Abstract\Services\RateServiceInterface;
use App\ViewModels\Mappers\RateMapper;
use App\ViewModels\RateViewModel;

/**
 *
 */
class RateService implements RateServiceInterface
{
    /**
     * @var RateRepositoryInterface
     */
    protected RateRepositoryInterface $rateRepository;

    /**
     * @param RateRepositoryInterface $rateRepository
     */
    public function __construct(RateRepositoryInterface $rateRepository)
    {
        $this->rateRepository = $rateRepository;
    }

    /**
     * @param int $id
     * @return RateViewModel
     */
    public function getById(int $id): RateViewModel
    {
        return RateMapper::modelToViewModel($this->rateRepository->getById($id));
    }

    /**
     * @param RateViewModel $viewModel
     * @return RateViewModel
     */
    public function save(RateViewModel $viewModel): RateViewModel
    {
        return RateMapper::modelToViewModel($this->rateRepository->save(RateMapper::viewModelToModel($viewModel, $this->rateRepository->getById($viewModel->id))));
    }
}

<?php

namespace App\Providers;

use App\Structure\Abstract\Repositories\CarrierRepositoryInterface;
use App\Structure\Abstract\Repositories\RateRepositoryInterface;
use App\Structure\Abstract\Repositories\SurchargesRepositoryInterface;
use App\Structure\Abstract\Services\CarrierServiceInterface;
use App\Structure\Abstract\Services\RateServiceInterface;
use App\Structure\Abstract\Services\SurchargesServiceInterface;
use App\Structure\Concrete\Repositories\CarrierRepository;
use App\Structure\Concrete\Repositories\RateRepository;
use App\Structure\Concrete\Repositories\SurchargesRepository;
use App\Structure\Concrete\Services\CarrierService;
use App\Structure\Concrete\Services\RateService;
use App\Structure\Concrete\Services\SurchargesService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        #repositories
        app()->bind(CarrierRepositoryInterface::class, CarrierRepository::class);
        app()->bind(SurchargesRepositoryInterface::class, SurchargesRepository::class);
        app()->bind(RateRepositoryInterface::class, RateRepository::class);

        #services
        app()->bind(SurchargesServiceInterface::class, SurchargesService::class);
        app()->bind(CarrierServiceInterface::class, CarrierService::class);
        app()->bind(RateServiceInterface::class, RateService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

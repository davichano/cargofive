<?php

namespace App\Structure\Abstract\Repositories;


use App\Models\Surcharge;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SurchargesRepository
 *
 * @package App\Structure\Repositories
 */
interface SurchargesRepositoryInterface
{
    /**
     * Get a surcharge by its ID.
     *
     * @param int $id
     * @return Surcharge
     */
    public function getById(int $id): Surcharge;

    /**
     * Get all surcharges.
     *
     */
    public function getAll(): Collection;

    /**
     * Get all surcharges.
     *
     */
    public function getAllFathers(): Collection;

    /**
     * Save a new surcharge, update the existing surcharge.
     *
     * @param Surcharge $surcharge
     * @return Surcharge
     */
    public function save(Surcharge $surcharge): Surcharge;

    public function getAllUngrouped(): Collection;
}

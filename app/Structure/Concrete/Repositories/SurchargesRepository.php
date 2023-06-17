<?php

namespace App\Structure\Concrete\Repositories;

use App\Models\Surcharge;
use App\Structure\Abstract\Repositories\SurchargesRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SurchargesRepository
 *
 * @package App\Structure\Repositories
 */
class SurchargesRepository implements SurchargesRepositoryInterface
{
    /**
     * Get a surcharge by its ID.
     *
     * @param int $id
     * @return Surcharge
     */
    public function getById(int $id): Surcharge
    {
        $data = Surcharge::where('id', $id)->first();
        if (!$data) $data = new Surcharge();
        return $data;
    }

    /**
     * Get all surcharges.
     *
     */
    public function getAll(): Collection
    {
        return Surcharge::get();
    }

    /**
     * Get all surcharges.
     *
     */
    public function getAllFathers(): Collection
    {
        return Surcharge::where('idFather', null)->get();
    }

    /**
     * Save a new surcharge, update the existing surcharge.
     *
     * @param Surcharge $surcharge
     * @return Surcharge
     */
    public function save(Surcharge $surcharge): Surcharge
    {
        if ($surcharge->id > 0) $surcharge->Update();
        else $surcharge->save();
        return $surcharge;
    }

    public function getAllUngrouped(): Collection
    {
        return Surcharge::where('isGrouped', false)->get();
    }
}

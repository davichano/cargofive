<?php

namespace App\Structure\Repositories;

use App\Models\Surcharge;

/**
 * Class SurchargesRepository
 *
 * @package App\Structure\Repositories
 */
class SurchargesRepository
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
    public function getAll()
    {
        return Surcharge::get();
    }

    /**
     * Get all surcharges.
     *
     */
    public function getAllFathers()
    {
        return Surcharge::where('idFather', null)->get();
    }

    /**
     * Save a new surcharge, update the existing surcharge.
     *
     * @param Surcharge $surcharge
     * @return Surcharge
     */
    public function save(Surcharge $surcharge)
    {
        if ($surcharge->id > 0) $surcharge->Update();
        else $surcharge->save();
        return $surcharge;
    }

    public function getAllUngrouped()
    {
        return Surcharge::where('isGrouped', false)->get();
    }
}

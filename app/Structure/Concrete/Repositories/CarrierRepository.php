<?php

namespace App\Structure\Concrete\Repositories;

use App\Models\Carrier;
use App\Structure\Abstract\Repositories\CarrierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class CarrierRepository implements CarrierRepositoryInterface
{
    /**
     * @param int $id
     * @return Carrier
     */
    public function getById(int $id): Carrier
    {
        $data = Carrier::where('id', $id)->first();
        if (!$data) $data = new Carrier();
        return $data;
    }

    /**
     * @param string $name
     * @return Carrier
     */
    public function getByName(string $name): Carrier
    {
        $data = Carrier::where('name', $name)->first();
        if (!$data) $data = new Carrier();
        return $data;
    }

    /**
     * Get all carriers.
     *
     */
    public function getAll(): Collection
    {
        return Carrier::get();
    }

    /**
     * @param Carrier $carrier
     * @return Carrier
     */
    public function save(Carrier $carrier): Carrier
    {
        if ($carrier->id > 0) $carrier->Update();
        else $carrier->save();
        return $carrier;
    }
}

<?php

namespace App\Structure\Concrete\Repositories;

use App\Models\Rate;
use App\Structure\Abstract\Repositories\RateRepositoryInterface;

/**
 * Repository for the Rate model, here I wrote every database query
 */
class RateRepository implements RateRepositoryInterface
{
    /**
     * @param int $id
     * @return Rate
     */
    public function getById(int $id): Rate
    {
        $data = Rate::where('id', $id)->first();
        if (!$data) $data = new Rate();
        return $data;
    }

    /**
     * @param Rate $rate
     * @return Rate
     */
    public function save(Rate $rate): Rate
    {
        if ($rate->id > 0) $rate->update();
        else $rate->save();
        return $rate;
    }

}

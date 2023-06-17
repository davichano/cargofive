<?php

namespace App\Structure\Abstract\Repositories;


use App\Models\Rate;

/**
 * Repository for the Rate model, here I wrote every database query
 */
interface RateRepositoryInterface
{
    /**
     * @param int $id
     * @return Rate
     */
    public function getById(int $id): Rate;

    /**
     * @param Rate $rate
     * @return Rate
     */
    public function save(Rate $rate): Rate;
}

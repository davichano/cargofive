<?php

namespace App\Structure\Abstract\Repositories;

use App\Models\Carrier;
use Illuminate\Database\Eloquent\Collection;

interface CarrierRepositoryInterface
{
    public function getById(int $id): Carrier;

    public function getByName(string $name): Carrier;

    public function getAll(): Collection;

    public function save(Carrier $carrier): Carrier;

}

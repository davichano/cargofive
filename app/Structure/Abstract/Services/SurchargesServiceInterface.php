<?php

namespace App\Structure\Abstract\Services;

use PhpOffice\PhpSpreadsheet\Reader\Exception;

interface SurchargesServiceInterface
{
    public function getAll(): array;

    public function getAllFathers(): array;

    public function group(): void;

    /**
     * @throws Exception
     */
    public function uploadDataFromExcel($exel): bool;

    public function joinGroups(int $idGroupA, int $idGroupB): bool;
}

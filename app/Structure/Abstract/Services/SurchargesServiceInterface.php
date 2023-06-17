<?php

namespace App\Structure\Abstract\Services;

use PhpOffice\PhpSpreadsheet\Reader\Exception;

interface SurchargesServiceInterface
{
    public function getAll(): array;

    public function getAllFathers(): array;

    public function group(): array;

    /**
     * @throws Exception
     */
    public function uploadDataFromExcel($exel): bool;
}

<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class CarDataImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
    }
}
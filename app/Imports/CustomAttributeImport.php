<?php

namespace App\Imports;

use App\Models\CustomAttribute;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomAttributeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CustomAttribute([
            //
        ]);
    }
}

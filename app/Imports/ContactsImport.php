<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    /**
    * @var array Keys: contact columns | Values: csv fields
    */
    private $mappings;

    public function __construct(array $mappings)
    {
        $this->mappings = $mappings;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // sanitize data
        return new Contact([
            'team_id' => $row[$this->mappings['team_id']],
            'name' => $row[$this->mappings['name']],
            'phone' => $row[$this->mappings['phone']],
            'email' => $row[$this->mappings['email']],
            'sticky_phone_number_id' => $row[$this->mappings['sticky_phone_number_id']]
        ]);
    }
}

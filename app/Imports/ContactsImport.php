<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\CustomAttribute;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    /**
    * @var array Keys: contact columns | Values: csv fields
    */
    private $contactMappings;

    /**
     * @var array Keys: custom keys | Values: custom values
     */
    private $customMappings;

    public function __construct(array $contactMappings, array $customMappings)
    {
        $this->contactMappings = $contactMappings;
        $this->customMappings = $customMappings;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // sanitize data
        $contact = new Contact([
            'team_id' => $row[$this->contactMappings['team_id']],
            'name' => $row[$this->contactMappings['name']],
            'phone' => $row[$this->contactMappings['phone']],
            'email' => $row[$this->contactMappings['email']],
            'sticky_phone_number_id' => $row[$this->contactMappings['sticky_phone_number_id']]
        ]);

        foreach ($this->customMappings as $key => $value) {
            // TODO: Create addCustomMapping method
            new CustomAttribute([
                'contact_id' => $contact->id,
                'key' => $row[$this->customMappings[$key]],
                'value' =>  $row[$this->customMappings[$value]]
            ]);
        }

        return $contact;
    }
}

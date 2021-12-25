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
        \Log::info('Mappings', ['contact_mapings' => $this->contactMappings, 'custom_mappings' => $this->customMappings]);
        // sanitize data
        $contact = Contact::create([
            'team_id' => isset($this->contactMappings['team_id']) ? $row[$this->contactMappings['team_id']] : null,
            'name' => isset($this->contactMappings['name']) ? $row[$this->contactMappings['name']] : null,
            'phone' => isset($this->contactMappings['phone']) ? $row[$this->contactMappings['phone']] : null,
            'email' => isset($this->contactMappings['email']) ? $row[$this->contactMappings['email']] : null,
            'sticky_phone_number_id' => isset($this->contactMappings['sticky_phone_number_id']) ? $row[$this->contactMappings['sticky_phone_number_id']] : null
        ]);

        foreach ($this->customMappings as $key => $value) {
            // TODO: Create addCustomMapping method
            CustomAttribute::create([
                'contact_id' => $contact->id,
                'key' => $key,
                'value' =>  $row[$this->customMappings[$key]]
            ]);
        }

        return $contact;
    }
}

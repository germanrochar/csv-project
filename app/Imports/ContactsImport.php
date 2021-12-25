<?php

namespace App\Imports;

use App\Mappings;
use App\Models\Contact;
use App\Models\CustomAttribute;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    /**
    * @var Mappings Keys: contact columns | Values: csv fields
    */
    private $contactMappings;

    /**
     * @var Mappings Keys: custom keys | Values: custom values
     */
    private $customMappings;

    public function __construct(Mappings $contactMappings, Mappings $customMappings)
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
        info('Mappings', [
            'contact_mapings' => $this->contactMappings,
            'custom_mappings' => $this->customMappings,
            'row' => $row
        ]);

        // sanitize data
        $contact = Contact::create([
            'team_id' => $this->contactMappings->has('team_id')
                ? $row[$this->contactMappings->get('team_id')]
                : null,
            'name' => $this->contactMappings->has('name')
                ? $row[$this->contactMappings->get('name')]
                : null,
            'phone' => $this->contactMappings->has('phone')
                ? $row[$this->contactMappings->get('phone')]
                : null,
            'email' => $this->contactMappings->has('email')
                ? $row[$this->contactMappings->get('email')]
                : null,
            'sticky_phone_number_id' => $this->contactMappings->has('sticky_phone_number_id')
                ? $row[$this->contactMappings->get('sticky_phone_number_id')]
                : null
        ]);

        foreach ($this->customMappings->getAll() as $key => $value) {
            $contact->addCustomAttribute($key, $row[$value]);
        }

        return $contact;
    }
}

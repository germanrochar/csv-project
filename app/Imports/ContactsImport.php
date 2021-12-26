<?php

namespace App\Imports;

use App\Mappings;
use App\Models\Contact;
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
        // Sanitize data
        $row = array_map(static function ($item) {
            return rtrim(addslashes($item));
        }, $row);

        $contact = Contact::create([
            'team_id' => $row[$this->contactMappings->get('team_id')],
            'phone' => $row[$this->contactMappings->get('phone')],
            'name' => $this->contactMappings->has('name')
                ? $row[$this->contactMappings->get('name')]
                : null,
            'email' => $this->contactMappings->has('email')
                ? filter_var($row[$this->contactMappings->get('email')], FILTER_SANITIZE_EMAIL)
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

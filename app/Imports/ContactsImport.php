<?php

namespace App\Imports;

use App\Mappings;
use App\Models\Contact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToCollection, WithHeadingRow
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
     * ToCollection concern is used instead of ToModel because we are storing two different models here.
     * If ToModel was used, the logic to create custom attributes will break the batch insert functionality.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            // Sanitize data
            $row = $row->map(function ($item) {
                return rtrim(addslashes($item));
            });

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
        }
    }
}

<?php

namespace App\Imports;

use App\Mappings;
use App\Models\Contact;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use RuntimeException;
use Throwable;

class ContactsImport implements ToCollection, WithHeadingRow
{
    /**
    * @var Mappings Keys: contact columns | Values: csv fields
    */
    private Mappings $mappings;

    public function __construct(Mappings $mappings)
    {
        $this->mappings = $mappings;
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

            try {
                $contact = Contact::create([
                    'team_id' => 1,
                    'phone' => $row[$this->mappings->get('phone')],
                    'name' => $this->mappings->has('name')
                        ? $row[$this->mappings->get('name')]
                        : null,
                    'email' => $this->mappings->has('email')
                        ? filter_var($row[$this->mappings->get('email')], FILTER_SANITIZE_EMAIL)
                        : null,
                    'sticky_phone_number_id' => $this->mappings->has('sticky_phone_number_id')
                        ? $row[$this->mappings->get('sticky_phone_number_id')]
                        : null
                ]);

                foreach ($this->mappings->getCustomMappings() as $key => $value) {
                    $contact->addCustomAttribute($key, $row[$value]);
                }
            } catch (QueryException $exception) {
                info('Invalid data found in csv file.', ['exception' => $exception]);
                throw $exception;
            } catch (Throwable $exception) {
                info('Contact or custom attributes failed to be imported.', ['exception' => $exception]);
                throw new RuntimeException('Something went wrong while importing contacts.');
            }
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportContactsFromCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_import_contacts_from_csv_file(): void
    {
        $header = 'name,phone_number,teams_ids,custom';
        $row1 = 'german,(555) 555-1234,45,lorem ipsum';
        $row2 = 'john,(555) 777-1234,11,lorem est'; // TODO: Remove this row at the end
        $content = implode("\n", [$header, $row1, $row2]);

        $csvFile = $this->createCsvFileFrom($content);

        // Set up mappings for csv file
        $contactFields = ['team_id', 'phone'];
        $csvFields = ['teams_ids', 'phone_number'];

        $customContactFields = ['custom', 'custom_two'];
        $customCsvFields = ['custom', 'name'];

        $data = [
            'contact_fields' => json_encode($contactFields),
            'csv_fields' => json_encode($csvFields),
            'custom_contact_fields' => json_encode($customContactFields),
            'custom_csv_fields' => json_encode($customCsvFields),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertsuccessful();

        $contacts = Contact::all();
        $this->assertCount(2, $contacts);

        // Check Contacts info
        $this->assertEquals('45', $contacts->first()->team_id);
        $this->assertEquals('(555) 555-1234', $contacts->first()->phone);

        $secondContact = $contacts->last();
        $this->assertEquals('11', $secondContact->team_id);
        $this->assertEquals('(555) 777-1234', $secondContact->phone);

        // Check Custom Attributes info
        // Contact #1 | Attributes
        $customAttributes = $contacts->first()->customAttributes()->get();
        $this->assertCount(2, $customAttributes);

        $this->assertEquals('custom', $customAttributes->first()->key);
        $this->assertEquals('lorem ipsum', $customAttributes->first()->value);

        $this->assertEquals('custom_two', $customAttributes->last()->key);
        $this->assertEquals('german', $customAttributes->last()->value);

        // Contact #2 | Attributes:
        $customAttributes = $contacts->last()->customAttributes()->get();
        $this->assertCount(2, $customAttributes);

        $this->assertEquals('custom', $customAttributes->first()->key);
        $this->assertEquals('lorem est', $customAttributes->first()->value);

        $this->assertEquals('custom_two', $customAttributes->last()->key);
        $this->assertEquals('john', $customAttributes->last()->value);
    }

    /** @test */
    public function non_snake_csv_headers_types_are_imported_properly(): void
    {
        $header = 'name,phone number,teams ids,custom';
        $row1 = 'german,(555) 555-1234,45,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        // Set up mappings for csv file
        $contactFields = ['team_id', 'phone'];
        $csvFields = ['teams ids', 'phone number'];

        $customContactFields = ['custom', 'custom_two'];
        $customCsvFields = ['custom', 'name'];

        $data = [
            'contact_fields' => json_encode($contactFields),
            'csv_fields' => json_encode($csvFields),
            'custom_contact_fields' => json_encode($customContactFields),
            'custom_csv_fields' => json_encode($customCsvFields),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertsuccessful();

        $contacts = Contact::all();
        $this->assertCount(1, $contacts);

        // Check Contacts info
        $this->assertEquals('45', $contacts->first()->team_id);
        $this->assertEquals('(555) 555-1234', $contacts->first()->phone);

        // Check Custom Attributes info
        $customAttributes = $contacts->first()->customAttributes()->get();
        $this->assertCount(2, $customAttributes);

        $this->assertEquals('custom', $customAttributes->first()->key);
        $this->assertEquals('lorem ipsum', $customAttributes->first()->value);

        $this->assertEquals('custom_two', $customAttributes->last()->key);
        $this->assertEquals('german', $customAttributes->last()->value);
    }

    /** @test */
    public function team_id_is_required_in_contact_mappings_list(): void
    {
        $header = 'name,phone_number,teams_ids,custom';
        $row1 = 'german,(555) 555-1234,45,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $contactFields = ['name', 'phone']; // Exclude team_id
        $csvFields = ['teams_ids', 'phone_number'];

        $customContactFields = ['custom', 'custom_two'];
        $customCsvFields = ['custom', 'name'];

        $data = [
            'contact_fields' => json_encode($contactFields),
            'csv_fields' => json_encode($csvFields),
            'custom_contact_fields' => json_encode($customContactFields),
            'custom_csv_fields' => json_encode($customCsvFields),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['contact_fields']);
    }
    /** @test */
    public function phone_is_required_in_mappings_list(): void
    {
        $header = 'name,phone_number,teams_ids,custom';
        $row1 = 'german,(555) 555-1234,45,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $contactFields = ['name', 'email']; // Exclude phone
        $csvFields = ['teams_ids', 'phone_number'];

        $customContactFields = ['custom', 'custom_two'];
        $customCsvFields = ['custom', 'name'];

        $data = [
            'contact_fields' => json_encode($contactFields),
            'csv_fields' => json_encode($csvFields),
            'custom_contact_fields' => json_encode($customContactFields),
            'custom_csv_fields' => json_encode($customCsvFields),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['contact_fields']);
    }

    /** @test */
    public function all_mappings_fields_are_required(): void
    {
        $header = 'name,phone_number,teams_ids,custom';
        $row1 = 'german,(555) 555-1234,45,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $data = [
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['contact_fields', 'csv_fields', 'custom_contact_fields', 'custom_csv_fields']);
    }

    /** @test */
    public function csv_file_is_required(): void
    {
        $contactFields = ['name', 'email'];
        $csvFields = ['teams_ids', 'phone_number'];

        $customContactFields = ['custom', 'custom_two'];
        $customCsvFields = ['custom', 'name'];

        $data = [
            'contact_fields' => json_encode($contactFields),
            'csv_fields' => json_encode($csvFields),
            'custom_contact_fields' => json_encode($customContactFields),
            'custom_csv_fields' => json_encode($customCsvFields)
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['csv_file']);
    }

    /** @test */
    public function all_contact_fields_mappings_must_exist_in_contacts_table(): void
    {
        $header = 'name,phone_number,teams_ids,custom';
        $row1 = 'german,(555) 555-1234,45,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $contactMappings = [
            'non_existing_column'=> 'teams_ids',
            'custom'=> 'phone_number',
            'phone'=> 'name',
            'team_id'=> 'custom'
        ];

        $customMappings = [
            'custom' => 'custom',
            'custom_two' => 'name'
        ];

        $data = [
            'contact_fields' => json_encode(array_keys($contactMappings)),
            'csv_fields' => json_encode(array_values($contactMappings)),
            'custom_contact_fields' => json_encode(array_keys($customMappings)),
            'custom_csv_fields' => json_encode(array_values($customMappings)),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['contact_fields']);
    }

    /** @test */
    public function all_mapping_field_pairs_must_have_the_same_length(): void
    {

    }

    /** @test */
    public function fields_in_csv_file_that_dont_have_matching_types_with_db_columns_throw_errors(): void
    {

    }

    /**
     * Generates a csv file based on the provided content
     * @param string $content
     * @return UploadedFile
     */
    protected function createCsvFileFrom(string $content): UploadedFile
    {
        return UploadedFile::fake()
            ->createWithContent(
                'contacts_list.csv',
                $content
            );
    }

}

<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\ImportJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportContactsFromCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_import_contacts_from_csv_file(): void
    {
        Storage::fake();

        $header = 'name,phone_number,custom,custom_two,email';
        $row1 = 'german,(555) 555-1234,lorem ipsum,testing,germçççan@test.com';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $mappings = [
            'phone_number' => 'phone',
            'email' => 'email',
            'custom' => 'custom',
            'custom_two' => 'another_custom_field',
        ];

        $data = [
            'mappings' => json_encode($mappings),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertSuccessful();

        $contacts = Contact::all();
        self::assertCount(1, $contacts);

        // Check Contacts info
        self::assertEquals(1, $contacts->first()->team_id);
        self::assertEquals('(555) 555-1234', $contacts->first()->phone);

        // Check Custom Attributes info
        $customAttributes = $contacts->first()->customAttributes()->get();
        self::assertCount(2, $customAttributes);

        self::assertEquals('custom', $customAttributes->first()->key);
        self::assertEquals('lorem ipsum', $customAttributes->first()->value);

        self::assertEquals('another_custom_field', $customAttributes->last()->key);
        self::assertEquals('testing', $customAttributes->last()->value);

        // Import Jobs Info
        $importJobs = ImportJob::all();
        self::assertCount(1, $importJobs);

        $importJob = $importJobs->first();
        self::assertSame('completed', $importJob->status);
        self::assertNotNull($importJob->job_id);
        self::assertNotNull($importJob->uuid);
        self::assertNull($importJob->error_message);
    }

    /** @test */
    public function non_snake_csv_headers_types_are_imported_properly(): void
    {
        Storage::fake();

        $header = 'name,phone number,custom field';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $mappings = [
            'phone_number' => 'phone',
            'custom field' => 'custom',
            'name' => 'custom_two',
        ];

        $data = [
            'mappings' => json_encode($mappings),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertSuccessful();

        $contacts = Contact::all();
        self::assertCount(1, $contacts);

        // Check Contacts info
        self::assertEquals(1, $contacts->first()->team_id);
        self::assertEquals('(555) 555-1234', $contacts->first()->phone);

        // Check Custom Attributes info
        $customAttributes = $contacts->first()->customAttributes()->get();
        self::assertCount(2, $customAttributes);

        self::assertEquals('custom', $customAttributes->first()->key);
        self::assertEquals('lorem ipsum', $customAttributes->first()->value);

        self::assertEquals('custom_two', $customAttributes->last()->key);
        self::assertEquals('german', $customAttributes->last()->value);

        // Import Jobs Info
        $importJobs = ImportJob::all();
        self::assertCount(1, $importJobs);

        $importJob = $importJobs->first();
        self::assertSame('completed', $importJob->status);
        self::assertNotNull($importJob->job_id);
        self::assertNotNull($importJob->uuid);
        self::assertNull($importJob->error_message);
    }

    /** @test */
    public function stores_a_failed_job_if_a_mappings_does_not_match_any_csv_header(): void
    {
        Storage::fake();

        $header = 'name,phone_number,custom,custom_two,email';
        $row1 = 'german,(555) 555-1234,lorem ipsum,testing,germçççan@test.com';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $mappings = [
            'phone_number' => 'phone',
            'invalid email' => 'email',
            'custom' => 'custom',
            'custom_two' => 'another_custom_field',
        ];

        $data = [
            'mappings' => json_encode($mappings),
            'csv_file' => $csvFile
        ];

        try {
            $this->post('/imports/contacts/csv', $data)
                ->assertSuccessful();
        } catch (RuntimeException $e) {
            //
        }

        self::assertDatabaseCount('contacts', 0);
        self::assertDatabaseCount('custom_attributes', 0);

        $importJobs = ImportJob::all();
        self::assertCount(1, $importJobs);

        $importJob = $importJobs->first();
        self::assertSame('failed', $importJob->status);
        self::assertNotNull($importJob->job_id);
        self::assertNotNull($importJob->uuid);
        self::assertSame('Something went wrong while importing contacts.', $importJob->error_message);
    }

    /** @test */
    public function stores_a_failed_job_if_csv_values_do_not_match_column_data_types(): void
    {
        Storage::fake();

        $header = 'name,phone_number,custom,custom_two,email';
        $row1 = 'german,(555) 555-1234,lorem ipsum,testing,germçççan@test.com';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $mappings = [
            'phone_number' => 'phone',
            'custom' => 'sticky_phone_number_id',
        ];

        $data = [
            'mappings' => json_encode($mappings),
            'csv_file' => $csvFile
        ];

        try {
            $this->post('/imports/contacts/csv', $data)
                ->assertSuccessful();
        } catch (RuntimeException $e) {
            //
        }

        self::assertDatabaseCount('contacts', 0);
        self::assertDatabaseCount('custom_attributes', 0);

        $importJobs = ImportJob::all();
        self::assertCount(1, $importJobs);

        $importJob = $importJobs->first();
        self::assertSame('failed', $importJob->status);
        self::assertNotNull($importJob->job_id);
        self::assertNotNull($importJob->uuid);
        self::assertSame('Mapped columns in the csv does not match the data types.', $importJob->error_message);
    }

    /** @test */
    public function phone_is_required_in_contact_mappings_list(): void
    {
        Storage::fake();
        $header = 'name,phone_number,custom';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $mappings = [
            'phone_number' => 'email',
            'name' => 'first_name',
        ];

        $data = [
            'mappings' => json_encode($mappings),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['mappings']);
    }

    /** @test */
    public function list_of_mappings_is_required(): void
    {
        Storage::fake();
        $header = 'name,phone_number,custom';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $data = [
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['mappings']);
    }

    /** @test */
    public function csv_file_is_required(): void
    {
        Storage::fake();
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
    public function only_csv_files_can_be_used_to_import_contacts(): void
    {
        Storage::fake();
        $excelFile = UploadedFile::fake()->create('excel.xlsx');

        $contactFields = ['name', 'email'];
        $csvFields = ['teams_ids', 'phone_number'];

        $customContactFields = ['custom', 'custom_two'];
        $customCsvFields = ['custom', 'name'];

        $data = [
            'contact_fields' => json_encode($contactFields),
            'csv_fields' => json_encode($csvFields),
            'custom_contact_fields' => json_encode($customContactFields),
            'custom_csv_fields' => json_encode($customCsvFields),
            'csv_file' => $excelFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['csv_file']);
    }

    /** @test */
    public function mappings_cannot_have_duplicate_values(): void
    {
        Storage::fake();
        $header = 'name,phone_number,custom';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $mappings = [
            'phone_number' => 'phone',
            'name' => 'custom',
            'custom' => 'phone',
        ];

        $data = [
            'mappings' => json_encode($mappings),
            'csv_file' => $csvFile
        ];

        $this->post('/imports/contacts/csv', $data)
            ->assertInvalid(['mappings']);
    }
}

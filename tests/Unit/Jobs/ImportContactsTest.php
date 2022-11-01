<?php

namespace Tests\Unit\Jobs;

use App\Events\ContactsImportFailed;
use App\Events\ContactsImportSucceeded;
use App\Jobs\ImportContacts;
use App\Mappings;
use App\Models\Contact;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportContactsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_contacts_from_a_csv_file(): void
    {
        Event::fake();
        Storage::fake();

        $header = 'csv_name,phone number,custom field';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvPath = UploadedFile::fake()
            ->createWithContent(
                'contacts_list.csv',
                $content
            )
            ->storeAs('/csv/files', 'contacts_list.csv');

        $mappings = new Mappings(['name', 'phone', 'custom_description'], ['csv_name', 'phone number', 'custom field']);

        (new ImportContacts($mappings, $csvPath))->handle();

        self::assertDatabaseHas('contacts', [
            'name' => 'german',
            'phone' => '(555) 555-1234',
        ]);

        $contact = Contact::query()->where('phone', '(555) 555-1234')->first();
        $customAttributes = $contact->customAttributes()->get();

        self::assertCount(1, $customAttributes);
        self::assertSame('custom_description', $customAttributes->first()->key);
        self::assertSame('lorem ipsum', $customAttributes->first()->value);

        Event::assertDispatched(ContactsImportSucceeded::class);
        Event::assertNotDispatched(ContactsImportFailed::class);
    }

    /** @test */
    public function it_throws_a_failed_broadcast_event_if_csv_mappings_do_not_match_any_csv_header(): void
    {
        Event::fake();
        Storage::fake();

        $header = 'csv_name,phone number,custom field';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvPath = UploadedFile::fake()
            ->createWithContent(
                'contacts_list.csv',
                $content
            )
            ->storeAs('/csv/files', 'contacts_list.csv');

        $mappings = new Mappings(['phone', 'custom_description'], ['phone number', 'my field']);

        (new ImportContacts($mappings, $csvPath))->handle();

        self::assertDatabaseCount('contacts',0);
        self::assertDatabaseCount('custom_attributes',0);

        Event::assertDispatched(ContactsImportFailed::class);
        Event::assertNotDispatched(ContactsImportSucceeded::class);
    }

    /** @test */
    public function it_throws_a_failed_broadcast_event_if_csv_values_do_not_match_column_data_types(): void
    {
        Event::fake();
        Storage::fake();

        $header = 'csv_name,phone number,custom field';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvPath = UploadedFile::fake()
            ->createWithContent(
                'contacts_list.csv',
                $content
            )
            ->storeAs('/csv/files', 'contacts_list.csv');

        $mappings = new Mappings(['phone', 'sticky_phone_number_id'], ['phone number', 'custom field']);

        (new ImportContacts($mappings, $csvPath))->handle();

        self::assertDatabaseCount('contacts',0);
        self::assertDatabaseCount('custom_attributes',0);

        Event::assertDispatched(ContactsImportFailed::class);
        Event::assertNotDispatched(ContactsImportSucceeded::class);
    }

    // @TODO: When validation is added to each row, add tests here
}

<?php

namespace Tests\Unit\Jobs;

use App\Events\ContactsImportFailed;
use App\Events\ContactsImportSucceeded;
use App\Jobs\ImportContacts;
use App\Mappings;
use App\Models\Contact;
use App\Models\ImportJob;
use Aws\Sqs\SqsClient;
use Illuminate\Container\Container;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Queue\Job as JobContract;

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
        $importJob = ImportJob::factory()->create([
            'status' => ImportJob::STATUS_STARTED,
            'uuid' => 'my-uuid'
        ]);

        self::assertDatabaseCount('import_jobs', 1);
        self::assertDatabaseHas('import_jobs', [
            'uuid' => 'my-uuid',
            'status' => 'started'
        ]);

        (new ImportContacts($mappings, $csvPath, $importJob->uuid))->handle();

        self::assertDatabaseHas('contacts', [
            'name' => 'german',
            'phone' => '(555) 555-1234',
        ]);
        $contact = Contact::query()->where('phone', '(555) 555-1234')->first();
        $customAttributes = $contact->customAttributes()->get();

        self::assertCount(1, $customAttributes);
        self::assertSame('custom_description', $customAttributes->first()->key);
        self::assertSame('lorem ipsum', $customAttributes->first()->value);

        self::assertDatabaseCount('import_jobs', 1);
        self::assertDatabaseHas('import_jobs', [
            'uuid' => 'my-uuid',
            'status' => 'started',
            'error_message' => null
        ]);

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

        $importJob = ImportJob::factory()->create([
            'status' => ImportJob::STATUS_STARTED,
            'uuid' => 'my-uuid'
        ]);

        try {
            (new ImportContacts($mappings, $csvPath, $importJob->uuid))->handle();
        } catch (RuntimeException $e) {
            self::assertSame('Something went wrong while importing contacts.', $e->getMessage());
        }

        self::assertDatabaseCount('contacts',0);
        self::assertDatabaseCount('custom_attributes',0);

        $importJobs = ImportJob::all();
        self::assertCount(1, $importJobs);

        $importJob = $importJobs->first();
        self::assertSame('my-uuid', $importJob->uuid);
        self::assertSame('started', $importJob->status);
        self::assertNull($importJob->error_message);

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

        $importJob = ImportJob::factory()->create([
            'status' => ImportJob::STATUS_STARTED,
            'uuid' => 'my-uuid'
        ]);

        try {
            (new ImportContacts($mappings, $csvPath, $importJob->uuid))->handle();
        } catch (RuntimeException $e) {
            self::assertSame('Mapped columns in the csv does not match the data types.', $e->getMessage());
        }

        self::assertDatabaseCount('contacts',0);
        self::assertDatabaseCount('custom_attributes',0);

        $importJobs = ImportJob::all();
        self::assertCount(1, $importJobs);

        $importJob = $importJobs->first();
        self::assertSame('my-uuid', $importJob->uuid);
        self::assertSame('started', $importJob->status);
        self::assertNull($importJob->error_message);

        Event::assertDispatched(ContactsImportFailed::class);
        Event::assertNotDispatched(ContactsImportSucceeded::class);
    }

    // @TODO: When validation is added to each row, add tests here
}

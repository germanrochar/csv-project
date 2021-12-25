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
        $row2 = 'john,(555) 777-1234,11,lorem est';
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

        // Assert Contacts info
        $this->assertEquals('45', $contacts->first()->team_id);
        $this->assertEquals('(555) 555-1234', $contacts->first()->phone);

        $secondContact = $contacts->last();
        $this->assertEquals('11', $secondContact->team_id);
        $this->assertEquals('(555) 777-1234', $secondContact->phone);

        // Assert Custom Attributes info
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

    /**
     * Generates a csv file based on the provided content
     * @param string $content
     * @return UploadedFile
     */
    protected function  createCsvFileFrom(string $content): UploadedFile
    {
        return UploadedFile::fake()
            ->createWithContent(
                'contacts_list.csv',
                $content
            );
    }

}

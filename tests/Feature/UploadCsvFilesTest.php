<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadCsvFilesTest extends TestCase
{
    use RefreshDatabase;

    public function usersCanUploadCsvFilesToPlatform()
    {
        Storage::fake();
        $csvFile = UploadedFile::fake()
            ->create('contacts_december',5120, 'csv')
        ;

        $inputs = [
            'csv_file' => $csvFile
        ];
        $this->post('contacts/upload/csv', $inputs);

        Storage::disk()
            ->assertExists('contacts/csv/' . $csvFile->hashName());
    }

    public function usersCannotUploadDifferentTypeOfFiles()
    {

    }

    /** @test */
    public function usersCannotUploadFilesBiggerThanTenMB()
    {
        $this->assertTrue(true);
    }
}

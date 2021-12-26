<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScanCsvFilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_csv_files_can_be_scanned(): void
    {
        $excelFile = UploadedFile::fake()->create('excel.xlsx');
        $data = [
            'csv_file' => $excelFile
        ];

        $this->post('/scan/csv', $data)
            ->assertInvalid('csv_file');
    }

    /** @test */
    public function csv_files_cannot_be_bigger_than_ten_mb(): void
    {
        $data = [
            'csv_file' => UploadedFile::fake()->create('file.csv', 40000)
        ];

        $this->post('/scan/csv', $data)
            ->assertInvalid('csv_file');
    }

    /** @test */
    public function csv_files_cannot_have_duplicate_headings(): void
    {
        $header = 'name,phone_number,name';
        $row1 = 'german,(555) 555-1234,lorem ipsum';
        $content = implode("\n", [$header, $row1]);

        $csvFile = $this->createCsvFileFrom($content);

        $data = [
            'csv_file' => $csvFile
        ];

        $this->post('/scan/csv', $data)
            ->assertStatus(400);
    }

    /** @test */
    public function csv_file_is_required(): void
    {
        $data = [];

        $this->post('/scan/csv', $data)
            ->assertInvalid(['csv_file']);
    }
}

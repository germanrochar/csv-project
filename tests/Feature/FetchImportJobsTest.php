<?php

namespace Tests\Feature;

use App\Models\ImportJob;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchImportJobsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function timestamp_field_is_required_to_fetch_import_jobs(): void
    {
        $this->getJson('/import-jobs')
            ->assertJsonValidationErrors(['tz' => 'The tz field is required.'])
        ;
    }

    /** @test */
    public function nobody_can_fetch_import_jobs_if_timezone_is_invalid(): void
    {
        $invalidTimezone = 'America/MyTimezone';

        $this->getJson("/import-jobs?tz=$invalidTimezone")
            ->assertJsonValidationErrors(['tz' => 'The tz must be a valid timezone.'])
        ;
    }

    /** @test */
    public function a_guest_can_fetch_all_the_import_jobs_triggered_today_sorted_by_created_date(): void
    {
        Carbon::setTestNow('2022-12-27 02:02:40');

        $importedJobOne = ImportJob::factory()->create(['created_at' => '2022-12-26 22:02:40']);
        $importedJobTwo = ImportJob::factory()->create(['created_at' => '2022-12-27 02:02:40']);

        $timezone = 'America/Ojinaga'; // GMT-6

        $this->getJson("/import-jobs?tz=$timezone")
            ->assertOk()
            ->assertJson([
                [
                    'id' => $importedJobTwo->id,
                    'uuid' => $importedJobTwo->uuid,
                    'status' => $importedJobTwo->status,
                    'error_message' => $importedJobTwo->error_message,
                    'created_at' => $importedJobTwo->created_at->toISOString(),
                    'updated_at' => $importedJobTwo->updated_at->toISOString(),
                ],
                [
                    'id' => $importedJobOne->id,
                    'uuid' => $importedJobOne->uuid,
                    'status' => $importedJobOne->status,
                    'error_message' => $importedJobOne->error_message,
                    'created_at' => $importedJobOne->created_at->toISOString(),
                    'updated_at' => $importedJobOne->updated_at->toISOString(),
                ]
            ])
        ;
    }

    /** @test */
    public function a_guest_cannot_fetch_import_jobs_triggered_before_today(): void
    {
        Carbon::setTestNow('2022-12-27 02:02:40');

        $timezone = 'America/Ojinaga'; // GMT-6

        ImportJob::factory()->create(['created_at' => Carbon::now()->subDay()]);

        $this->getJson("/import-jobs?tz=$timezone")
            ->assertOk()
            ->assertJson([])
        ;
    }
}

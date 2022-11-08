<?php

namespace Tests\Feature;

use App\Models\ImportJob;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowImportJobsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_see_all_the_import_jobs_triggered_today_sorted_by_created_date(): void
    {
        $importedJobOne = ImportJob::factory()->create(['created_at' => Carbon::now()->subHour()->tz('America/New_York')]);
        $importedJobTwo = ImportJob::factory()->create(['created_at' => Carbon::now()->tz('America/New_York')]);

        $this->get('/import-jobs')
            ->assertOk()
            ->assertJson([
                [
                    'id' => $importedJobTwo->id,
                    'job_id' => $importedJobTwo->job_id,
                    'uuid' => $importedJobTwo->uuid,
                    'status' => $importedJobTwo->status,
                    'error_message' => $importedJobTwo->error_message,
                    'created_at' => $importedJobTwo->created_at->toISOString(),
                    'updated_at' => $importedJobTwo->updated_at->toISOString(),
                ],
                [
                    'id' => $importedJobOne->id,
                    'job_id' => $importedJobOne->job_id,
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
    public function a_guest_cannot_see_the_import_jobs_triggered_before_today(): void
    {
        ImportJob::factory()->create(['created_at' => Carbon::now()->tz('America/New_York')->subDay()]);

        $this->get('/import-jobs')
            ->assertOk()
            ->assertJson([])
        ;
    }
}

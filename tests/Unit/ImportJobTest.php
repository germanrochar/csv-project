<?php

namespace Tests\Unit;

use App\Models\ImportJob;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_filter_jobs_by_only_those_triggered_today(): void
    {
        $importJobOne = ImportJob::factory()->create([
            'job_id' => 1,
            'uuid' => 'myuuid',
            'status' => ImportJob::STATUS_COMPLETED,
            'created_at' => Carbon::now()->tz('America/New_York')
        ]);

        ImportJob::factory()->create(['created_at' => Carbon::now()->subDays(10)->tz('America/New_York')]);

        self::assertEquals(
            [
                [
                    'id' => 1,
                    'job_id' => 1,
                    'uuid' => 'myuuid',
                    'status' => 'completed',
                    'error_message' => null,
                    'created_at' => $importJobOne->created_at->toISOString(),
                    'updated_at' => $importJobOne->updated_at->toISOString(),
                ]
            ],
            ImportJob::query()->importedToday()->get()->toArray()
        );
    }
}

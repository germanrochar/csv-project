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
        Carbon::setTestNow('2022-12-27 02:02:40');
        $timezone = 'America/Ojinaga';

        $importJobOne = ImportJob::factory()->create([
            'id' => 1,
            'uuid' => 'myuuid',
            'status' => ImportJob::STATUS_COMPLETED,
            'created_at' => Carbon::now()
        ]);

        ImportJob::factory()->create(['created_at' => Carbon::now()->subDays(10)]);

        self::assertEquals(
            [
                [
                    'id' => 1,
                    'uuid' => 'myuuid',
                    'status' => 'completed',
                    'error_message' => null,
                    'created_at' => $importJobOne->created_at->toISOString(),
                    'updated_at' => $importJobOne->updated_at->toISOString(),
                ]
            ],
            ImportJob::query()->importedToday($timezone)->get()->toArray()
        );
    }

    /** @test */
    public function it_supports_different_timezones(): void
    {
        Carbon::setTestNow('2022-12-26 02:02:40');
        $timezone = 'Asia/Dhaka';

        ImportJob::factory()->create([
            'id' => 1,
            'uuid' => 'myuuid',
            'status' => ImportJob::STATUS_COMPLETED,
            'created_at' => '2022-12-25 22:02:40',
            'updated_at' => '2022-12-25 22:02:40',
        ]);

        ImportJob::factory()->create(['created_at' => '2022-12-25 17:59:00']);

        self::assertEquals(
            [
                [
                    'id' => 1,
                    'uuid' => 'myuuid',
                    'status' => 'completed',
                    'error_message' => null,
                    'created_at' => Carbon::parse('2022-12-25 22:02:40')->toISOString(),
                    'updated_at' => Carbon::parse('2022-12-25 22:02:40')->toISOString(),
                ]
            ],
            ImportJob::query()->importedToday($timezone)->get()->toArray()
        );
    }
}

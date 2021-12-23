<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadCsvFilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usersCanUploadCsvFilesToPlatform()
    {
        $this->assertTrue(true);
    }
}

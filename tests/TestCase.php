<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Generates a csv file based on the provided content
     *
     * @param string $content
     * @return UploadedFile
     */
    protected function createCsvFileFrom(string $content): UploadedFile
    {
        return UploadedFile::fake()
            ->createWithContent(
                'contacts_list.csv',
                $content
            );
    }
}

<?php

namespace App\Jobs;

use App\Imports\ContactsImport;
use App\Mappings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ImportContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Mappings $mappings;
    private string $csvPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mappings $mappings, string $csvPath)
    {
        $this->mappings = $mappings;
        $this->csvPath = $csvPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            Excel::import(new ContactsImport($this->mappings), $this->csvPath, 's3');
        } catch (QueryException $e) {
            $this->fail('Please check the data types of your mapped fields in csv file. Some data types does not match.');
            // TODO: catch validation issues
        }
    }
}

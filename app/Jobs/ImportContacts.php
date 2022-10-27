<?php

namespace App\Jobs;

use App\Events\ContactsImportFailed;
use App\Events\ContactsImportSucceeded;
use App\Imports\ContactsImport;
use App\Mappings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;

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
        // If import fails, we want to fail the job and fire an pusher event that will be listened by the UI
        // If import succeeds, we want to fire a pusher event that will change the behavior in the UI
        try {
            Excel::import(new ContactsImport($this->mappings), $this->csvPath, 's3');
        } catch (QueryException|RuntimeException $e) {
            ContactsImportFailed::dispatch();
            $this->fail();
        }

        ContactsImportSucceeded::dispatch();
    }
}

<?php

namespace App\Jobs;

use App\Events\ContactsImportFailed;
use App\Events\ContactsImportSucceeded;
use App\Imports\ContactsImport;
use App\Mappings;
use App\Models\ImportJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    private string $jobId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mappings $mappings, string $csvPath, string $jobId)
    {
        $this->mappings = $mappings;
        $this->csvPath = $csvPath;
        $this->jobId = $jobId;
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
        } catch (RuntimeException $e) {
            ContactsImportFailed::dispatch($this->jobId, $e->getMessage());
            throw $e;
        }

        ContactsImportSucceeded::dispatch($this->jobId);
    }
}

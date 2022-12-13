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
        // @TODO: Create two separate jobs. Create the import job from the controller. Then, process the import here. Finally, reflect the change in the UI.
        ImportJob::create([
            'job_id' => $this->job->getJobId(),
            'uuid' => $this->job->uuid(),
            'status' => ImportJob::STATUS_STARTED,
        ]);

        try {
            Excel::import(new ContactsImport($this->mappings), $this->csvPath, 's3');
        } catch (RuntimeException $e) {
            ContactsImportFailed::dispatch($this->job, $e->getMessage());
            throw $e;
        }

        ContactsImportSucceeded::dispatch($this->job);
    }
}

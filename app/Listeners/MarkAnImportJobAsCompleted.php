<?php

namespace App\Listeners;

use App\Events\ContactsImportSucceeded;
use App\Events\ImportSucceeded;
use App\Models\ImportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkAnImportJobAsCompleted
{
    public function handle(ContactsImportSucceeded $event): void
    {
        $importJob = ImportJob::query()
            ->where('job_id', $event->job->getJobId())
            ->where('uuid', $event->job->uuid())
            ->firstOrFail();

        $importJob->update([
            'status' => ImportJob::STATUS_COMPLETED,
            'error_message' => null,
        ]);

        ImportSucceeded::dispatch();
    }
}

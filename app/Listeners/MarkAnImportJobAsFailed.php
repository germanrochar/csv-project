<?php

namespace App\Listeners;

use App\Events\ContactsImportFailed;
use App\Events\ImportFailed;
use App\Models\ImportJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkAnImportJobAsFailed
{
    public function handle(ContactsImportFailed $event): void
    {
        $importJob = ImportJob::query()
            ->where('job_id', $event->jobId)
            ->where('uuid', $event->jobId)
            ->firstOrFail();

        info('Fetched failed job', ['job' => $importJob]);

        $importJob->update([
            'status' => ImportJob::STATUS_FAILED,
            'error_message' => $event->errorMessage
        ]);

        ImportFailed::dispatch();
    }
}

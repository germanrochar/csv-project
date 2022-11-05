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
        $job = ImportJob::query()
            ->where('job_id', $event->job->getJobId())
            ->where('uuid', $event->job->uuid())
            ->firstOrFail();

        $job->update([
            'state' => ImportJob::STATUS_FAILED,
            'error_message' => $event->errorMessage
        ]);

        ImportFailed::dispatch();
    }
}

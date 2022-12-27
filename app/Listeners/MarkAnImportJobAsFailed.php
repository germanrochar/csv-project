<?php

namespace App\Listeners;

use App\Events\ContactsImportFailed;
use App\Events\ImportFailed;
use App\Models\ImportJob;

class MarkAnImportJobAsFailed
{
    public function handle(ContactsImportFailed $event): void
    {
        $importJob = ImportJob::query()
            ->where('uuid', $event->jobId)
            ->firstOrFail();

        $importJob->update([
            'status' => ImportJob::STATUS_FAILED,
            'error_message' => $event->errorMessage
        ]);

        ImportFailed::dispatch();
    }
}

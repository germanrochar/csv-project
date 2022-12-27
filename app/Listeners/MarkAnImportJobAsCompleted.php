<?php

namespace App\Listeners;

use App\Events\ContactsImportSucceeded;
use App\Events\ImportSucceeded;
use App\Models\ImportJob;

class MarkAnImportJobAsCompleted
{
    public function handle(ContactsImportSucceeded $event): void
    {
        $importJob = ImportJob::query()
            ->where('uuid', $event->jobId)
            ->firstOrFail();

        $importJob->update([
            'status' => ImportJob::STATUS_COMPLETED,
            'error_message' => null,
        ]);

        ImportSucceeded::dispatch();
    }
}

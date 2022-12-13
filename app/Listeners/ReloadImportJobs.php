<?php

namespace App\Listeners;

use App\Events\ImportJobCreated;

class ReloadImportJobs
{
    /**
     * Handle the event.
     *
     * @param  ImportJobCreated  $event
     * @return void
     */
    public function handle(ImportJobCreated $event): void
    {
        info('Reloading import jobs...');
        \App\Events\ReloadImportJobs::dispatch();
    }
}

<?php

namespace App\Listeners;

use App\Events\ImportJobCreated;

class NotifyImportJobStarted
{
    /**
     * Handle the event.
     *
     * @param  ImportJobCreated  $event
     * @return void
     */
    public function handle(ImportJobCreated $event): void
    {
        \App\Events\ImportJobStarted::dispatch();
    }
}

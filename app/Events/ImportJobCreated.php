<?php

namespace App\Events;

use App\Models\ImportJob;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportJobCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ImportJob $importJob;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ImportJob $importJob)
    {
        $this->importJob = $importJob;
    }
}

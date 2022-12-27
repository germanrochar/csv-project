<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactsImportFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(readonly string $jobId, readonly string $errorMessage)
    {
    }
}

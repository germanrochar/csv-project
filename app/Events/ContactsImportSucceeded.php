<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactsImportSucceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(readonly string $jobId)
    {
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactsImportSucceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(readonly Job $job)
    {
    }
}

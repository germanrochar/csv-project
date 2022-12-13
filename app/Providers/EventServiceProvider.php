<?php

namespace App\Providers;

use App\Events\ContactsImportFailed;
use App\Events\ContactsImportSucceeded;
use App\Events\ImportJobCreated;
use App\Listeners\MarkAnImportJobAsCompleted;
use App\Listeners\MarkAnImportJobAsFailed;
use App\Listeners\NotifyImportJobStarted;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(
            ContactsImportSucceeded::class,
            [MarkAnImportJobAsCompleted::class, 'handle']
        );

        Event::listen(
            ContactsImportFailed::class,
            [MarkAnImportJobAsFailed::class, 'handle']
        );

        Event::listen(
            ImportJobCreated::class,
            [NotifyImportJobStarted::class, 'handle']
        );
    }
}

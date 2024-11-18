<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

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
        parent::boot();
        // Register the listener for global Eloquent events
        Event::listen(
            [
                'eloquent.creating: *', // Triggered before a model is created
                'eloquent.updating: *', // Triggered before a model is updated
                'eloquent.saving: *', // Triggered before a model is saving
                'eloquent.deleting: *', // Triggered before a model is deleted
            ],
            \App\Listeners\CacheInvalidationListener::class
        );
    }
}

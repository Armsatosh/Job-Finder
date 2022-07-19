<?php declare(strict_types=1);

namespace App\Providers;

use App\Events\JobCreated;
use App\Events\JobDeleted;
use App\Events\JobEdited;
use App\Listeners\JobListener;
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
        JobCreated::class => [
            JobListener::class,
        ],
        JobEdited::class => [
            JobListener::class,
        ],
        JobDeleted::class => [
            JobListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(function (JobCreated $event) {
            //
        });
    }
}

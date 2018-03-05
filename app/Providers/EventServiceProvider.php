<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Session\Completed' => [
            'App\Listeners\Session\CreateSessionEntry',
        ],
        'App\Events\Student\Session\AttendanceMarked' => [
            'App\Listeners\Student\Session\AttendanceMarked',
        ],
        'App\Events\Student\Batch\BatchTransferred' => [
            'App\Listeners\Student\Batch\BatchTransferred',
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

        //
    }
}

<?php

namespace App\Providers;

use App\Events\PaperRecordItemSaved;
use App\Events\PaperRecordSubmitted;
use App\Listeners\RecordScoreAndError;
use App\Listeners\UpdateRecordCountAndScore;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        PaperRecordItemSaved::class => [
            RecordScoreAndError::class,
        ],

        PaperRecordSubmitted::class => [
            UpdateRecordCountAndScore::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

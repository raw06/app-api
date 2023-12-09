<?php

namespace App\Listeners;

use App\Events\AppUninstalled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AppUninstalledListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AppUninstalled  $event
     * @return void
     */
    public function handle(AppUninstalled $event)
    {
        //
    }
}

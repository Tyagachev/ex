<?php

namespace App\Listeners\Share;

use App\Events\Share\ShareCount;

class ShareCountListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ShareCount $event): void
    {
        $event->obj->increment('share_count');
    }
}

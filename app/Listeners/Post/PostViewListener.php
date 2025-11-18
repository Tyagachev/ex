<?php

namespace App\Listeners\Post;

use App\Events\Post\PostViewCount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class PostViewListener
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
    public function handle(PostViewCount $event): void
    {
        $userId = $event->user ? $event->user->id : null;
        $event->post->addView($userId, $event->ipAddress, $event->userAgent);
    }
}

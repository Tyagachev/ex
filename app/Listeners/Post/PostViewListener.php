<?php

namespace App\Listeners\Post;

use App\Events\Post\PostViewCount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        $event->post->increment('view');
    }
}

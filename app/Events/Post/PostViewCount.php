<?php

namespace App\Events\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostViewCount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public object $post;
    public object | null $user;
    public string $ipAddress;
    public string $userAgent;

    public function __construct(Post $post, ?User $user = null, $ipAddress = null, $userAgent = null)
    {
        $this->post = $post;
        $this->user = $user;
        $this->ipAddress = $ipAddress ?? request()->ip();
        $this->userAgent = $userAgent ?? request()->userAgent();
    }
}

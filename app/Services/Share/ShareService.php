<?php

namespace App\Services\Share;

use App\Services\Share\ShareFactory\ShareFactory;

class ShareService
{
    public function storeShare(array $share)
    {
        $factory = ShareFactory::make($share['type']);
        return $factory->share($share['id']);
    }
}

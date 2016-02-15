<?php

namespace RayRutjes\DddBootstrap\Messaging;

use RayRutjes\DddEssentials\Messaging\Message;

class FQCNameResolver implements MessageNameResolver
{
    /**
     * @param Message $message
     *
     * @return string
     */
    public function resolve(Message $message): string
    {
        return get_class($message);
    }
}

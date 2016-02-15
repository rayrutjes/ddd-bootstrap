<?php

namespace RayRutjes\DddBootstrap\Messaging;

use RayRutjes\DddEssentials\Messaging\Message;

interface MessageNameResolver
{
    /**
     * @param Message $message
     *
     * @return string
     */
    public function resolve(Message $message): string;
}

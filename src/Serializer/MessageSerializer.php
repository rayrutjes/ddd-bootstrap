<?php

namespace RayRutjes\DddBootstrap\Serializer;

use RayRutjes\DddEssentials\Messaging\Message;

interface MessageSerializer
{
    /**
     * @param Message $message
     *
     * @return mixed
     */
    public function serialize(Message $message);

    /**
     * @param mixed  $data
     * @param string $messageType
     *
     * @return Message
     */
    public function deserialize($data, $messageType): Message;
}

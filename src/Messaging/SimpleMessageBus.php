<?php

namespace RayRutjes\DddBootstrap\Messaging;

use RayRutjes\DddEssentials\Messaging\Message;

final class SimpleMessageBus implements MessageBus
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @param Message $message
     *
     * @return mixed
     */
    public function publish(Message $message)
    {
        $handler = $this->findHandlerFor($message);

        return $handler->handle($message);
    }

    /**
     * @param string         $messageName
     * @param MessageHandler $handler
     */
    public function subscribe($messageName, MessageHandler $handler)
    {
        if (isset($this->handlers[$messageName])) {
            throw new \RuntimeException(sprintf('A message handler has already been subscribed for %s', $messageName));
        }
        $this->handlers[$messageName] = $handler;
    }

    /**
     * @param Message $message
     *
     * @return MessageHandler
     */
    private function findHandlerFor(Message $message)
    {
        $messageName = $message->name();
        if (!isset($this->handlers[$messageName])) {
            throw new \RuntimeException(sprintf('No message handler found for %s', $messageName));
        }

        return $this->handlers[$messageName];
    }
}

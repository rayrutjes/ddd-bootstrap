<?php

namespace RayRutjes\DddBootstrap\Messaging;

use RayRutjes\DddEssentials\Messaging\Message;

abstract class ConventionalMessageHandler implements MessageHandler
{
    /**
     * @param Message $message
     *
     * @return mixed
     */
    final public function handle(Message $message)
    {
        $method = $this->getHandleMethodFor($message);
        if (!is_callable([$this, $method])) {
            throw new \RuntimeException(sprintf('%s method has not been implemented in %s.', $method, __CLASS__));
        }

        // Todo: maybe check the signature with reflection to enforce the typing.

        return $method($message);
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    final private function getHandleMethodFor(Message $message)
    {
        return 'handle' . (new \ReflectionClass($message))->getShortName();
    }
}

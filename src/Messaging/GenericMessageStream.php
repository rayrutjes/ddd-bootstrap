<?php

namespace RayRutjes\DddBootstrap\Messaging;

use RayRutjes\DddBootstrap\Util\InternalIterator;
use RayRutjes\DddEssentials\Messaging\Message;

final class GenericMessageStream extends InternalIterator implements MessageStream
{
    /**
     * @param array $messages
     *
     * @return GenericMessageStream
     */
    public static function fromArray(array $messages): GenericMessageStream
    {
        foreach ($messages as $message) {
            if (!$message instanceof Message) {
                throw new \InvalidArgumentException(sprintf('Invalid Message, got: %s.', get_class($message)));
            }
        }
        parent::__construct(new \ArrayIterator($messages));
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $iterator = $this->getIterator();
        if ($iterator instanceof \Countable) {
            return $iterator->count();
        }

        return 0;
    }
}

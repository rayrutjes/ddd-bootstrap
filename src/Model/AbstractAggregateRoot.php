<?php

namespace RayRutjes\DddBootstrap\Model;

use RayRutjes\DddEssentials\Model\AggregateRoot;
use RayRutjes\DddEssentials\Model\DomainEvent;
use RayRutjes\DddEssentials\Messaging\GenericMessageStream;
use RayRutjes\DddEssentials\Messaging\MessageStream;

abstract class AbstractAggregateRoot implements AggregateRoot
{
    /**
     * @var int
     */
    private $version = 0;

    /**
     * @var array
     */
    private $uncommittedChanges = [];

    /**
     * @param MessageStream $events
     *
     * @return AggregateRoot
     */
    final public static function loadFromHistory(MessageStream $events): AggregateRoot
    {
        $aggregateRoot = new static();
        foreach ($events as $event) {
            $aggregateRoot->applyChange($event, false);
        }

        return $aggregateRoot;
    }

    /**
     * @return int
     */
    final public function version(): int
    {
        return $this->version;
    }

    /**
     * @param DomainEvent $event
     * @param bool        $isNew
     */
    final protected function applyChange(DomainEvent $event, $isNew = true)
    {
        $method = $this->getApplyMethodFor($event);
        $method($event);

        if ($isNew === true) {
            $this->uncommittedChanges[] = $event;
        } else {
            $this->version++;
        }
    }

    /**
     * @param $event
     *
     * @return string
     */
    final private function getApplyMethodFor($event): string
    {
        return 'apply' . (new \ReflectionClass($event))->getShortName();
    }

    /**
     * @return MessageStream
     */
    final public function uncommittedChanges(): MessageStream
    {
        return GenericMessageStream::fromArray($this->uncommittedChanges);
    }

    /**
     * Clears uncommitted changes and updates the version of the aggregate.
     */
    final public function markChangesAsCommitted()
    {
        $this->version += count($this->uncommittedChanges);
        $this->uncommittedChanges = [];
    }
}

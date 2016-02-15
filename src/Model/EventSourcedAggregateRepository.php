<?php

namespace RayRutjes\DddBootstrap\Model;

use RayRutjes\DddEssentials\Persistence\EventStore;
use RayRutjes\DddEssentials\Messaging\MessageStream;
use RayRutjes\DddEssentials\Model\AggregateRoot;
use RayRutjes\DddEssentials\Model\Repository;

abstract class EventSourcedAggregateRepository implements Repository
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @param EventStore $eventStore
     */
    final public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param $aggregateId
     *
     * @return AggregateRoot
     */
    final public function load($aggregateId): AggregateRoot
    {
        $events = $this->eventStore->getAggregateHistory($aggregateId);
        $aggregate = $this->loadAggregateFromHistory($events);

        return $aggregate;
    }

    /**
     * @param MessageStream $events
     *
     * @return AggregateRoot
     */
    final private function loadAggregateFromHistory(MessageStream $events): AggregateRoot
    {
        $aggregateType = $this->aggregateType();

        return $aggregateType::loadFromHistory($events);
    }

    /**
     * @return string
     */
    abstract protected function aggregateType(): string;

    /**
     * @param AggregateRoot $aggregate
     */
    final public function save(AggregateRoot $aggregate)
    {
        $changes = $aggregate->uncommittedChanges();
        if (0 === $changes->count()) {
            return;
        }

        $this->eventStore->saveAggregateChanges($aggregate->id(), $changes, $aggregate->version());
        $aggregate->markChangesAsCommitted();
    }
}

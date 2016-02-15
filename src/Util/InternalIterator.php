<?php

namespace RayRutjes\DddBootstrap\Util;

abstract class InternalIterator implements \Iterator
{
    /**
     * @var \Iterator
     */
    private $iterator;

    /**
     * @param \Iterator $iterator
     */
    final protected function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @return \Iterator
     */
    final protected function getIterator()
    {
        return $this->iterator;
    }

    /**
     * {@inheritdoc}
     */
    final public function current()
    {
        return $this->iterator->current();
    }

    /**
     * {@inheritdoc}
     */
    final public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    final public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    final public function valid(): bool
    {
        return $this->iterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    final public function rewind()
    {
        if (null === $this->iterator) {
            throw new \LogicException('Internal iterator is missing.');
        }
        $this->iterator->rewind();
    }
}

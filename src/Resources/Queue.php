<?php

namespace Queue\Resources;

/**
 * Class Queue is a Value Object.
 */
class Queue extends Object implements QueueInterface
{
    /**
     * Name of queue.
     * @var string
     */
    private $name;

    /**
     * Queue constructor.
     * @param string $name
     * @param array $attributes
     */
    public function __construct($name, array $attributes = array())
    {
        $this->name = $name;
        parent::__construct($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
}

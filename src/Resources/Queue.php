<?php

namespace Queue\Resources;

/**
 * Class Queue is a Value Object.
 * @package Driver
 */
class Queue extends Object
{
    /**
     * Name of queue.
     * @var string
     */
    private $name;

    /**
     * Attributes of queue.
     * @var array
     */
    private $attributes;

    public function __construct($name, array $attributes = array())
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}

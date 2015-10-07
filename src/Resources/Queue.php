<?php

namespace Queue\Resources;

/**
 * Class Queue is a Value Object.
 * @package Driver
 */
class Queue
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
    /**
     * @var Binding[]
     */
    private $bindings;

    public function __construct($name, array $attributes = array())
    {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->bindings = array();
    }

    public static function createFromConfiguration($name, array $attributes = array())
    {
        return new self($name, $attributes);
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

    /**
     * @param Tunnel $tunnel
     * @param array $patternKeys
     */
    public function bind(Tunnel $tunnel, array $patternKeys = array())
    {
        $this->bindings[] = new Binding($tunnel, $this, $patternKeys);
    }
}

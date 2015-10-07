<?php

namespace Queue\Resources;

use InvalidArgumentException;

/**
 * Class Tunnel is a Value Object.
 * @package Driver
 */
class Tunnel
{
    const TYPE_DIRECT = 'direct';
    const TYPE_FANOUT = 'fanout';
    const TYPE_TOPIC = 'topic';
    const TYPE_HEADERS = 'headers';

    /**
     * Name of queue.
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * Attributes of queue.
     * @var array
     */
    private $attributes;
    /**
     * @var Binding[]
     */
    private $bindings;

    public function __construct($name, $type, array $attributes = array())
    {
        $this->name = $name;
        $this->type = $type;
        $this->attributes = $attributes;
        $this->bindings = array();
    }

    /**
     * @param $name
     * @param array $attributes
     * @return Tunnel
     * @throws InvalidArgumentException
     */
    public static function createFromConfiguration($name, array $attributes = array())
    {
        if (!isset($attributes['type'])) {
            throw new InvalidArgumentException("Type was not found for {$name} tunnel.");
        }

        $type = $attributes['type'];
        unset($attributes['type']);

        return new self($name, $type, $attributes);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return Binding[]
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    public function bind(Queue $queue, array $patternKeys = array())
    {
        $this->bindings[] = new Binding($this, $queue, $patternKeys);
    }
}

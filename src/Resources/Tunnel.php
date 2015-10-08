<?php

namespace Queue\Resources;

/**
 * Class Tunnel is a Value Object.
 * @package Driver
 */
class Tunnel extends Object
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
     * @var array
     */
    private $routes;

    public function __construct($name, $type, array $attributes = array())
    {
        $this->name = $name;
        $this->type = $type;
        $this->attributes = $attributes;
        $this->routes = array();
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
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param string $name
     * @return Queue[]
     */
    public function getQueuesFromRoute($name)
    {
        if ($this->hasRoute($name)) {
            return $this->routes[$name];
        }
        return array();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRoute($name)
    {
        return isset($this->routes[$name]);
    }

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    public function addRoute($queueName, $routeName)
    {
        $queues = array();
        if (isset($this->routes[$routeName])) {
            $queues = $this->routes[$routeName];
        }
        if (in_array($queueName, $queues)) {
            array_push($queues, $queue);
        }
        $this->routes[$routeName] = $queues;
    }
}

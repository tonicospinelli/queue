<?php

namespace Queue\Resources;

/**
 * Class Tunnel is a Value Object.
 * @package Driver
 */
class Tunnel extends Object implements TunnelInterface
{
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
     * @var array
     */
    private $routes;

    public function __construct($name, $type, array $attributes = array())
    {
        parent::__construct($attributes);
        $this->name = $name;
        $this->type = $type;
        $this->routes = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueuesFromRoute($name)
    {
        if ($this->hasRoute($name)) {
            return $this->routes[$name];
        }
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function hasRoute($name)
    {
        return isset($this->routes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function setRoutes(array $routes)
    {
        $this->routes = array();
        foreach ($routes as $routeName => $queues) {
            foreach ($queues as $queue) {
                $this->addRoute($queue, $routeName);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addRoute($queueName, $routeName = '')
    {
        $queues = array();
        if (isset($this->routes[$routeName])) {
            $queues = $this->routes[$routeName];
        }
        if (!in_array($queueName, $queues)) {
            array_push($queues, $queueName);
        }
        $this->routes[$routeName] = $queues;
    }
}

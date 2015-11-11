<?php

namespace Queue\Resources;

/**
 * Class Exchange is a Value Object.
 * @package Driver
 */
class Exchange extends Object implements ExchangeInterface
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
    private $bindings;

    public function __construct($name, $type, array $attributes = array())
    {
        parent::__construct($attributes);
        $this->name = $name;
        $this->type = $type;
        $this->bindings = array();
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
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueuesFromBinding($name)
    {
        if ($this->hasBinding($name)) {
            return $this->bindings[$name];
        }
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function hasBinding($name)
    {
        return isset($this->bindings[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function setBindings(array $bindings)
    {
        $this->bindings = array();
        foreach ($bindings as $routingKey => $queues) {
            foreach ($queues as $queue) {
                $this->addBinding($queue, $routingKey);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addBinding($queueName, $routingKey = '')
    {
        $queues = array();
        if (isset($this->bindings[$routingKey])) {
            $queues = $this->bindings[$routingKey];
        }
        if (!in_array($queueName, $queues)) {
            array_push($queues, $queueName);
        }
        $this->bindings[$routingKey] = $queues;
    }
}

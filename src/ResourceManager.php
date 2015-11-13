<?php

namespace Queue;

use Queue\Resources\Queue;
use Queue\Resources\Route;
use Queue\Resources\Exchange;

class ResourceManager
{
    /**
     * @var Queue[]
     */
    private $queues;
    /**
     * @var Exchange[]
     */
    private $exchanges;

    public function __construct()
    {
        $this->queues = array();
        $this->exchanges = array();
    }

    public static function createFromConfiguration(array $configuration, Driver $driver = null)
    {
        $manager = new static();
        $classes = $configuration['classes'];
        unset($configuration['classes']);

        foreach ($configuration['queues'] as $name => $data) {
            $queueClass = $classes['queue'];
            $ref = new \ReflectionClass($queueClass);
            /** @var Queue $queue */
            $queue = $ref->newInstance($name, $data);
            $manager->addQueue($queue);
        }
        foreach ($configuration['exchanges'] as $name => $data) {
            $type = $data['type'];
            unset($data['type']);

            $queueClass = $classes['exchange'];

            $bindings = array();
            foreach ($data['bindings'] as $routingKey => $queues) {
                $bindings[$routingKey] = $queues['queues'];
            }
            unset($data['bindings']);

            $ref = new \ReflectionClass($queueClass);
            /** @var Exchange $exchange */
            $exchange = $ref->newInstance($name, $type, $data);

            $exchange->setBindings($bindings);
            $manager->addExchange($exchange);
        }
        return $manager;
    }


    /**
     * @return Queue[]
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * @param string $name
     * @return Queue
     */
    public function getQueue($name)
    {
        return $this->queues[$name];
    }

    /**
     * @param Queue $queue
     */
    public function addQueue(Queue $queue)
    {
        $this->queues[$queue->getName()] = $queue;
    }

    /**
     * @return Exchange[]
     */
    public function getExchanges()
    {
        return $this->exchanges;
    }

    /**
     * @param string $name
     * @return Exchange
     */
    public function getExchange($name)
    {
        return $this->exchanges[$name];
    }

    public function addExchange(Exchange $exchange)
    {
        $this->exchanges[$exchange->getName()] = $exchange;
    }
}

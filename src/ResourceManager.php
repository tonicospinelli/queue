<?php

namespace Queue;

use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

class ResourceManager
{
    /**
     * @var Queue[]
     */
    private $queues;
    /**
     * @var Tunnel[]
     */
    private $tunnels;

    public function __construct()
    {
        $this->queues = array();
        $this->tunnels = array();
    }

    public static function createFromConfiguration(array $configuration)
    {
        $manager = new static();
        $classes = $configuration['classes'];
        unset($configuration['classes']);

        foreach ($configuration['queues'] as $name => $data) {
            $queueClass = $classes['queue'];
            $ref = new \ReflectionClass($queueClass);
            /** @var Queue $queue */
            $queue = $ref->newInstance($name);
            $queue->setData($data);
            $manager->addQueue($queue);
        }
        foreach ($configuration['tunnels'] as $name => $data) {
            $type = $data['type'];
            unset($data['type']);
            $queueClass = $classes['tunnel'];
            $ref = new \ReflectionClass($queueClass);
            /** @var Tunnel $tunnel */
            $tunnel = $ref->newInstance($name, $type);
            $tunnel->setData($data);
            $manager->addTunnel($tunnel);
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
     * @return Tunnel[]
     */
    public function getTunnels()
    {
        return $this->tunnels;
    }

    /**
     * @param string $name
     * @return Tunnel
     */
    public function getTunnel($name)
    {
        return $this->tunnels[$name];
    }

    public function addTunnel(Tunnel $tunnel)
    {
        $this->tunnels[$tunnel->getName()] = $tunnel;
    }
}

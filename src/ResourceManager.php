<?php

namespace Queue;

use Queue\Resources\Binding;
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
    /**
     * @var array
     */
    private $bindings;

    public function factoryFromConfiguration($type, $name, $arguments)
    {
        $className = $arguments['class'];
        unset($arguments['class']);

        if ($type == 'bindings') {
            $arguments['tunnel'] = $this->getTunnel($arguments['tunnel']);
            $arguments['queue'] = $this->getQueue($arguments['queue']);
        } else {
            $arguments = array($name, $arguments);
        }
        return call_user_func_array(array($className, 'createFromConfiguration'), $arguments);
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

    /**
     * @return Binding[]
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    public function addBinding(Binding $binding)
    {
        $this->bindings[] = $binding;
    }

    /**
     * @param string $type
     * @param object $resourceObject
     */
    public function addResource($type, $resourceObject)
    {
        switch ($type) {
            case 'queue':
            case 'queues':
                $this->addQueue($resourceObject);
                break;
            case 'tunnel':
            case 'tunnels':
                $this->addTunnel($resourceObject);
                break;
            case 'binding':
            case 'bindings':
                /** @var Binding $resourceObject */
                $tunnel = $this->getTunnel($resourceObject->getTunnel()->getName());
                $tunnel->bind($resourceObject->getQueue(), $resourceObject->getPatternKeys());
                $this->addBinding($resourceObject);
                break;
        }
    }
}

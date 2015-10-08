<?php

namespace Queue\Component;

use Queue\Connection as WrapperConnection;
use Queue\ResourceManager;
use Queue\Resources\Route;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

class ResourceTool
{
    /**
     * @var WrapperConnection
     */
    private $connection;
    /**
     * @var ResourceManager
     */
    private $resourceManager;

    public function __construct(WrapperConnection $connection, ResourceManager $resourceManager)
    {
        $this->connection = $connection;
        $this->resourceManager = $resourceManager;
    }

    /**
     * @return WrapperConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return ResourceManager
     */
    public function getResourceManager()
    {
        return $this->resourceManager;
    }

    public function raiseResources()
    {
        $this->raiseQueues($this->getResourceManager()->getQueues());
        $this->raiseTunnels($this->getResourceManager()->getTunnels());
    }

    /**
     * @param Queue[] $queues
     */
    private function raiseQueues(array $queues)
    {
        foreach ($queues as $queue) {
            $this->getConnection()->createQueue($queue);
        }
    }

    /**
     * @param Tunnel[] $tunnels
     */
    private function raiseTunnels(array $tunnels)
    {
        foreach ($tunnels as $tunnel) {
            $this->getConnection()->createTunnel($tunnel);
            foreach ($tunnel->getRoutes() as $routeKey => $queues) {
                foreach ($queues as $queue) {
                    $this->raiseRoutes($queue, $tunnel->getName(), $routeKey);
                }
            }
        }
    }

    private function raiseRoutes($queue, $tunnel, $routeKey = '')
    {
        $this->getConnection()->bind($queue, $tunnel, $routeKey);
    }
}

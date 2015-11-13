<?php

namespace Queue\Component;

use Queue\Connection as WrapperConnection;
use Queue\ResourceManager;
use Queue\Resources\QueueInterface;
use Queue\Resources\Route;
use Queue\Resources\Queue;
use Queue\Resources\Exchange;
use Queue\Resources\ExchangeInterface;

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
        $this->raiseExchanges($this->getResourceManager()->getExchanges());
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
     * @param Exchange[] $exchanges
     */
    private function raiseExchanges(array $exchanges)
    {
        foreach ($exchanges as $exchange) {
            $this->getConnection()->createExchange($exchange);
            foreach ($exchange->getBindings() as $routingKey => $queues) {
                foreach ($queues as $queue) {
                    $queueObject = $this->getResourceManager()->getQueue($queue);
                    $this->raiseRoutes($queueObject, $exchange, $routingKey);
                }
            }
        }
    }

    private function raiseRoutes(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->getConnection()->bind($queue, $exchange, $routingKey);
    }
}

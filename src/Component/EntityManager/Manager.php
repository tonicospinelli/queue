<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace Queue\Component\EntityManager;


use Queue\Driver\Connection;
use Queue\Driver\Exception\DivergentEntityException;
use Queue\Entity\AbstractBind;
use Queue\Entity\AbstractEntity;
use Queue\Entity\AbstractExchange;
use Queue\Entity\AbstractQueue;

class Manager
{
    /**
     * @var AbstractBind[]
     */
    private $binds = array();

    /**
     * @var AbstractQueue[]
     */
    private $queues = array();

    /**
     * @var AbstractExchange[]
     */
    private $exchanges = array();

    /**
     * @var bool
     */
    private $recreate = false;

    /**
     * @param bool $recreateOnError
     */
    public function __construct($recreateOnError = false)
    {
        $this->recreate = $recreateOnError;
    }

    /**
     * @param AbstractEntity $entity
     */
    public function addEntity(AbstractEntity $entity)
    {
        if ($entity instanceof AbstractQueue) {
            $this->queues[] = $entity;
        } elseif ($entity instanceof AbstractExchange) {
            $this->exchanges[] = $entity;
        } elseif ($entity instanceof AbstractBind) {
            $this->binds[] = $entity;
        }
    }

    /**
     * @param AbstractEntity[] $entities
     */
    public function setEntities(array $entities)
    {
        $this->resetEntities();
        foreach ($entities as $entity) {
            $this->addEntity($entity);
        }
    }

    /**
     *
     */
    private function resetEntities()
    {
        $this->binds = array();
        $this->exchanges = array();
        $this->queues = array();
    }

    /**
     * @param Connection $connection
     */
    public function update(Connection $connection)
    {
        foreach ($this->queues as $queue) {
            $this->updateQueue($connection, $queue);
        }
        foreach ($this->exchanges as $exchange) {
            $this->updateExchange($connection, $exchange);
        }
        foreach ($this->binds as $bind) {
            $this->updateBind($connection,$bind);
        }
    }

    /**
     * @param Connection $connection
     * @param AbstractQueue $queue
     * @throws DivergentEntityException
     * @throws \Exception
     */
    private function updateQueue(Connection $connection, AbstractQueue $queue)
    {
        try {
            $connection->createQueue($queue);
        } catch (DivergentEntityException $e) {
            if ($this->recreate) {
                $connection->dropQueue($queue);
                $connection->createQueue($queue);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param Connection $connection
     * @param AbstractExchange $exchange
     * @throws DivergentEntityException
     * @throws \Exception
     */
    private function updateExchange(Connection $connection, AbstractExchange $exchange)
    {
        try {
            $connection->createExchange($exchange);
        } catch (DivergentEntityException $e) {
            if ($this->recreate) {
                $connection->dropExchange($exchange);
                $connection->createExchange($exchange);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param Connection $connection
     * @param AbstractBind $bind
     */
    private function updateBind(Connection $connection, AbstractBind $bind)
    {
        if ($bind->isDeprecated()) {
            $connection->dropBind($bind);
        } else {
            $connection->createBind($bind);
        }
    }
}
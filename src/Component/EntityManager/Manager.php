<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace Queue\Component\EntityManager;


use Queue\Driver\Connection;
use Queue\Entity\AbstractBind;
use Queue\Entity\AbstractEntity;
use Queue\Entity\AbstractExchange;
use Queue\Entity\AbstractQueue;

class Manager
{
    /**
     * @var AbstractEntity[]
     */
    private $binds = array();

    /**
     * @var AbstractEntity[]
     */
    private $queues = array();

    /**
     * @var AbstractEntity[]
     */
    private $exchanges = array();

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
     * @param Connection $connection
     */
    public function update(Connection $connection)
    {
        foreach ($this->queues as $entity) {
            $entity->update($connection);
        }
        foreach ($this->exchanges as $entity) {
            $entity->update($connection);
        }
        foreach ($this->binds as $entity) {
            $entity->update($connection);
        }
    }

    private function resetEntities()
    {
        $this->binds = array();
        $this->exchanges = array();
        $this->queues = array();
    }
}
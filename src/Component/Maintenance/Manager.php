<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace Queue\Component\Maintenance;


use Queue\Driver\Connection;
use Queue\Entity\AbstractEntity;

class Manager
{
    /**
     * @var AbstractEntity[]
     */
    private $entities = array();

    /**
     * @param AbstractEntity $entity
     */
    public function addEntity(AbstractEntity $entity)
    {
        $this->entities[] = $entity;
    }

    /**
     * @param AbstractEntity[] $entities
     */
    public function setEntities(array $entities)
    {
        $this->entities = $entities;
    }

    /**
     * @param Connection $connection
     */
    public function update(Connection $connection)
    {
        foreach ($this->entities as $entity) {
            $entity->update($connection);
        }
    }
} 
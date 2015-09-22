<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;


use Queue\Driver\Connection as DriverConnection;

/**
 * Class AbstractEntity
 * @package Queue\Entity
 */
abstract class AbstractEntity
{

    /**
     * @param DriverConnection $connection
     * @return void
     */
    abstract protected function execute(DriverConnection $connection);

    /**
     * @param DriverConnection $connection
     */
    public function update(DriverConnection $connection)
    {
        $this->execute($connection);
    }
} 
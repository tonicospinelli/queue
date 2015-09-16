<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Migration\Entity;


use Queue\Driver\Connection as DriverConnection;

abstract class AbstractEntity
{
    /**
     * @var DriverConnection
     */
    protected $connection;

    public function __construct(DriverConnection $connection)
    {
        $this->connection = $connection;
    }

    abstract protected function execute();

    public function update()
    {
        $this->execute();
    }
} 
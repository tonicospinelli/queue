<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

namespace Queue;

use Queue\Driver\Connection as DriverConnection;

abstract class AbstractProcess
{
    /**
     * @var DriverConnection
     */
    private $connection;

    /**
     * @param DriverConnection $connection
     */
    public function __construct(DriverConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return DriverConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }



}

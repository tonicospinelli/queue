<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

namespace Queue;

use Queue\Driver\Connection as DriverConnection;

abstract class AbstractQueue implements InterfaceQueue
{
    /**
     * 10 second
     */
    const MESSAGE_TIME_TO_LIVE = 10000;

    /**
     * @var DriverConnection
     */
    private $connection;

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

    abstract public function getWorkingQueueName();

    abstract public function getWorkingExchangeName();

    public function getQueueArguments()
    {
        return array();
    }

    /**
     * @return Exchange
     */
    public function getExchange()
    {
        return $this->getConnection()->getExchange();
    }

    public function getTimeToLiveInMilliseconds()
    {
        return self::MESSAGE_TIME_TO_LIVE;
    }
}

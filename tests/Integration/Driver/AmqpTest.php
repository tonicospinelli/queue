<?php

namespace QueueTest\Integration\Driver;

use Queue\Configuration;
use Queue\Driver as BaseDriver;
use Queue\Resources\Amqp\ExchangeInterface;
use Queue\Resources\Amqp\QueueInterface;

class AmqpTest extends TestCase
{
    /**
     * @return \Queue\Driver\Connection
     */
    public function createConnection()
    {
        return new BaseDriver\Amqp\Connection(
            new Configuration(
                BaseDriver::AMQP,
                RABBIT_HOST,
                RABBIT_PORT,
                RABBIT_USERNAME,
                RABBIT_PASSWORD,
                array('no_wait' => true)
            ),
            new BaseDriver\Amqp\Driver()
        );
    }

    /**
     * @param BaseDriver\Connection $connection
     * @return ExchangeInterface
     */
    protected function createExchange(BaseDriver\Connection $connection)
    {
        /** @var ExchangeInterface $exchange */
        $exchange = parent::createExchange($connection);
        $exchange->setDurable();
        $exchange->setAutoDelete(false);
        return $exchange;
    }

    /**
     * @param BaseDriver\Connection $connection
     * @return QueueInterface
     */
    public function createQueue(BaseDriver\Connection $connection)
    {
        /** @var QueueInterface $queue */
        $queue = parent::createQueue($connection);
        $queue->setDurable();
        $queue->setAutoDelete(false);
        return $queue;
    }
}

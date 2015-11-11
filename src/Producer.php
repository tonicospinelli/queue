<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Resources\MessageInterface;
use Queue\Resources\ExchangeInterface;

abstract class Producer extends AbstractProcess implements ProducerInterface
{
    /**
     * @var ExchangeInterface
     */
    private $exchange;

    public function __construct(DriverConnection $connection, ExchangeInterface $exchange)
    {
        parent::__construct($connection);
        $this->exchange = $exchange;
    }

    /**
     * {@inheritdoc}
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($message)
    {
        return $this->getConnection()->getDriver()->createMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    final public function publish(MessageInterface $message, $routingKey = '')
    {
        $this->getConnection()->publish($message, $this->getExchange(), $routingKey);
    }
}

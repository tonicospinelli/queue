<?php

namespace Queue;

use Queue\Driver\Connection as BaseConnection;
use Queue\Driver\Exception\DriverException;
use Queue\Resources\MessageInterface;
use Queue\Resources\QueueInterface;
use Queue\Resources\ExchangeInterface;

interface Driver
{
    const AMQP = 'amqp';
    const IN_MEMORY = 'inMemory';

    /**
     * @return string
     */
    public function getName();

    /**
     * Attempts to create a connection with the queue.
     *
     * @param ConfigurationInterface $configuration
     *
     * @return BaseConnection The database connection.
     *
     * @throws DriverException
     */
    public function connect(ConfigurationInterface $configuration);

    /**
     * @param string $name
     * @param array $attributes
     * @return QueueInterface
     */
    public function createQueue($name, array $attributes = array());

    /**
     * @param string $name
     * @param string $type
     * @param array $attributes
     * @return ExchangeInterface
     */
    public function createExchange($name, $type, array $attributes = array());

    /**
     * @param string $message
     * @param array $properties
     * @param string $uid
     * @return MessageInterface
     */
    public function createMessage($message, array $properties = array(), $uid = null);
}

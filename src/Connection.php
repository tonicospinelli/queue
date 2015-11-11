<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Resources\MessageInterface;
use Queue\Resources\QueueInterface;
use Queue\Resources\ExchangeInterface;

class Connection implements DriverConnection
{
    /**
     * @var DriverConnection
     */
    private $connection;

    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(ConfigurationInterface $configuration, Driver $driver)
    {
        $this->driver = $driver;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return $this->getDriver()->getName();
    }

    /**
     * @return ConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return DriverConnection
     */
    protected function connect()
    {
        if (!$this->connection) {
            $this->connection = $this->getDriver()->connect($this->getConfiguration());
        }
        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->connect()->close();
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->connect()->publish($message, $exchange, $routingKey);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(QueueInterface $queue)
    {
        return $this->connect()->fetchOne($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function ack(MessageInterface $message)
    {
        $this->connect()->ack($message);
    }

    /**
     * {@inheritdoc}
     */
    public function nack(MessageInterface $message)
    {
        $this->connect()->nack($message);
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue(QueueInterface $queue)
    {
        $this->connect()->createQueue($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(QueueInterface $queue)
    {
        $this->connect()->deleteQueue($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange(ExchangeInterface $exchange)
    {
        $this->connect()->createExchange($exchange);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExchange(ExchangeInterface $exchange)
    {
        $this->connect()->deleteExchange($exchange);
    }

    /**
     * {@inheritdoc}
     */
    public function bind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->connect()->bind($queue, $exchange, $routingKey);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->connect()->unbind($queue, $exchange, $routingKey);
    }
}

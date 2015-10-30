<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Driver\MessageInterface;

use Queue\Entity\AbstractBind as BindEntity;
use Queue\Entity\AbstractExchange as ExchangeEntity;
use Queue\Entity\AbstractExchange;
use Queue\Entity\AbstractQueue as QueueEntity;
use Queue\Entity\AbstractQueue;

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
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
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
    public function publish(MessageInterface $message, AbstractExchange $exchange)
    {
        $this->connect()->publish($message, $exchange);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($message, array $properties = array(), $id = null)
    {
        return $this->connect()->prepare($message, $properties, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(AbstractQueue $queue)
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
    public function createQueue(QueueEntity $queue)
    {
        $this->connect()->createQueue($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function dropQueue(QueueEntity $queue)
    {
        $this->connect()->dropQueue($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange(ExchangeEntity $exchange)
    {
        $this->connect()->createExchange($exchange);
    }

    /**
     * {@inheritdoc}
     */
    public function dropExchange(ExchangeEntity $exchange)
    {
        $this->connect()->dropExchange($exchange);
    }

    /**
     * {@inheritdoc}
     */
    public function createBind(BindEntity $bind)
    {
        $this->connect()->createBind($bind);
    }

    /**
     * {@inheritdoc}
     */
    public function dropBind(BindEntity $bind)
    {
        $this->connect()->dropBind($bind);
    }
}

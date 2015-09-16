<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Driver\MessageInterface;

use Queue\Migration\Entity\AbstractBind as BindEntity;
use Queue\Migration\Entity\AbstractExchange as ExchangeEntity;
use Queue\Migration\Entity\AbstractQueue as QueueEntity;

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
    public function publish(MessageInterface $message, ProducerInterface $producer)
    {
        $this->connect()->publish($message, $producer);
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
    public function fetchOne(ConsumerInterface $consumer)
    {
        return $this->connect()->fetchOne($consumer);
    }

    /**
     * {@inheritdoc}
     */
    public function getExchange()
    {
        return $this->connect()->getExchange();
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
     * @param QueueEntity $queue
     * @return void
     */
    public function createQueue(QueueEntity $queue)
    {
        $this->connect()->createQueue($queue);
    }

    /**
     * @param QueueEntity $queue
     * @return void
     */
    public function dropQueue(QueueEntity $queue)
    {
        $this->connect()->dropQueue($queue);
    }

    /**
     * @param ExchangeEntity $queue
     * @return void
     */
    public function createExchange(ExchangeEntity $queue)
    {
        $this->connect()->createExchange($queue);
    }

    /**
     * @param ExchangeEntity $queue
     * @return void
     */
    public function dropExchange(ExchangeEntity $queue)
    {
        $this->connect()->dropExchange($queue);
    }

    /**
     * @param BindEntity $queue
     * @return void
     */
    public function createBind(BindEntity $queue)
    {
        $this->connect()->createBind($queue);
    }

    /**
     * @param BindEntity $queue
     * @return void
     */
    public function dropBind(BindEntity $queue)
    {
        $this->connect()->dropBind($queue);
    }
}

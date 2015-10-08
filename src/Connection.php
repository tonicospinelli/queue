<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

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
    public function publish(MessageInterface $message, Tunnel $tunnel, $patternKey = '')
    {
        $this->connect()->publish($message, $tunnel, $patternKey);
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
    public function fetchOne($queueName)
    {
        return $this->connect()->fetchOne($queueName);
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
    public function createQueue(Queue $queue)
    {
        $this->connect()->createQueue($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(Queue $queue)
    {
        $this->connect()->deleteQueue($queue);
    }

    /**
     * {@inheritdoc}
     */
    public function createTunnel(Tunnel $tunnel)
    {
        $this->connect()->createTunnel($tunnel);
    }

    /**
     * {@inheritdoc}
     */
    public function dropTunnel(Tunnel $tunnel)
    {
        $this->connect()->dropTunnel($tunnel);
    }

    /**
     * {@inheritdoc}
     */
    public function bind($tunnel, $queue, $patternKey = '')
    {
        $this->connect()->bind($tunnel, $queue, $patternKey);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind($tunnel, $queue, $patternKey = '')
    {
        $this->connect()->unbind($tunnel, $queue, $patternKey);
    }
}

<?php

namespace Queue\Driver\InMemory;

use Queue\Configuration;
use Queue\ConfigurationInterface;
use Queue\Driver as BaseDriver;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\QueueInterface;
use Queue\Resources\Exchange;
use Queue\Resources\ExchangeInterface;

class Connection implements \Queue\Driver\Connection
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    protected $connection;

    /**
     * @var BaseDriver
     */
    protected $driver;

    /**
     * @var Queue[]
     */
    private $queues;

    /**
     * @var Exchange[]
     */
    private $exchanges;

    public function __construct(ConfigurationInterface $configuration, BaseDriver $driver)
    {
        $this->queues = array();
        $this->exchanges = array();
        $this->configuration = $configuration;
        $this->driver = $driver;
        $this->connection = array();
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
     * @param string $queue
     * @return resource
     */
    private function getConnection($queue)
    {
        if (!isset($this->connection[$queue])) {
            $this->connection[$queue] = msg_get_queue(rand());
        }
        return $this->connection[$queue];
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, ExchangeInterface $exchange, $routingKey = '')
    {
        foreach ($exchange->getBindings() as $routeName => $queues) {
            if (!preg_match('/' . $routeName . '/', $routingKey)) {
                continue;
            }
            foreach ($queues as $queue) {
                $this->send($queue->getName(), $message);
            }
        }
    }

    /**
     * @param string $queue
     * @param MessageInterface $message
     */
    private function send($queue, MessageInterface $message)
    {
        $connection = $this->getConnection($queue);
        msg_send($connection, 1, $message->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(QueueInterface $queue)
    {
        $connection = $this->getConnection($queue->getName());
        $msg_type = null;
        $msg = null;
        $max_msg_size = 512;
        msg_receive($connection, 1, $msg_type, $max_msg_size, $message, true, MSG_IPC_NOWAIT);
        if (!$message) {
            return null;
        }
        return $this->getDriver()->createMessage($message, array('queue' => $queue->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function ack(MessageInterface $message)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function nack(MessageInterface $message)
    {
        if ($queue = $message->getAttribute('queue')) {
            $this->send($queue, $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue(QueueInterface $queue)
    {
        $this->queues[$queue->getName()] = $queue;
        $this->getConnection($queue->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(QueueInterface $queue)
    {
        $connection = $this->getConnection($queue->getName());
        msg_remove_queue($connection);
        unset($this->queues[$queue->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange(ExchangeInterface $exchange)
    {
        $this->exchanges[$exchange->getName()] = $exchange;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExchange(ExchangeInterface $exchange)
    {
        unset($this->exchanges[$exchange->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function bind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->exchanges[$exchange->getName()]->addBinding($queue, $routingKey);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $bindings = $this->exchanges[$exchange->getName()]->getBindings();

        if (!isset($bindings[$routingKey])) {
            return;
        }

        $queues = array_filter($bindings[$routingKey], function ($queueName) use ($queue) {
            return $queueName != $queue;
        });

        $bindings[$routingKey] = $queues;
        $this->exchanges[$exchange->getName()]->setBindings($bindings);
    }
}

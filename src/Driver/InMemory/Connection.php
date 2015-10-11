<?php

namespace Queue\Driver\InMemory;

use Queue\Configuration;
use Queue\ConfigurationInterface;
use Queue\Resources\Message;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

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
     * @var Queue[]
     */
    private $queues;
    /**
     * @var Tunnel[]
     */
    private $tunnels;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->queues = array();
        $this->tunnels = array();
        $this->configuration = $configuration;
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
    public function prepare($message, array $properties = array(), $id = null)
    {
        return new Message($message, $properties, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, Tunnel $tunnel, $patternKey = '')
    {
        foreach ($tunnel->getRoutes() as $routeName => $queues) {
            if (!preg_match('/' . $routeName . '/', $patternKey)) {
                continue;
            }
            foreach ($queues as $queue) {
                $this->send($queue, $message);
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
    public function fetchOne($queueName)
    {
        $connection = $this->getConnection($queueName);
        $msg_type = null;
        $msg = null;
        $max_msg_size = 512;
        msg_receive($connection, 1, $msg_type, $max_msg_size, $message, true, MSG_IPC_NOWAIT);
        if (!$message) {
            return null;
        }
        return $this->prepare($message, array('queue' => $queueName));
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
        if ($message->isRequeue() && ($queue = $message->getProperty('queue'))) {
            $this->send($queue, $this->prepare($message->getBody()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue(Queue $queue)
    {
        $this->queues[$queue->getName()] = $queue;
        $this->getConnection($queue->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(Queue $queue)
    {
        $connection = $this->getConnection($queue->getName());
        msg_remove_queue($connection);
        unset($this->queues[$queue->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function createTunnel(Tunnel $tunnel)
    {
        $this->tunnels[$tunnel->getName()] = $tunnel;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTunnel(Tunnel $tunnel)
    {
        unset($this->tunnels[$tunnel->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function bind($queue, $tunnel, $routeKey = '')
    {
        $this->tunnels[$tunnel]->addRoute($queue, $routeKey);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind($queue, $tunnel, $routeKey = '')
    {
        $routes = $this->tunnels[$tunnel]->getRoutes();

        if (!isset($routes[$routeKey])) {
            return;
        }

        $queues = array_filter($routes[$routeKey], function ($queueName) use ($queue) {
            return $queueName != $queue;
        });

        $routes[$routeKey] = $queues;
        $this->tunnels[$tunnel]->setRoutes($routes);
    }
}

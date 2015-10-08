<?php

namespace Queue\Driver\InMemory;

use Queue\ConfigurationInterface;
use Queue\Resources\Message;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

class Connection implements \Queue\Driver\Connection
{
    /**
     * @var array
     */
    protected $connection;

    public function __construct(ConfigurationInterface $configuration)
    {
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
        foreach ($tunnel->getRoutes() as $binding) {
            if (in_array($patternKey, $binding->getName())) {
                $connection = $this->getConnection($binding->getQueue()->getName());
                msg_send($connection, 1, $message->getBody());
            }
        }
    }

    private function getConnection($queue)
    {
        if (!isset($this->connection[$queue])) {
            $this->connection[$queue] = msg_get_queue(rand());
        }
        return $this->connection[$queue];
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
        return $this->prepare($message);
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
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue(Queue $queue)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(Queue $queue)
    {
        $connection = $this->getConnection($queue->getName());
        msg_remove_queue($connection);
    }

    /**
     * {@inheritdoc}
     */
    public function createTunnel(Tunnel $tunnel)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function dropTunnel(Tunnel $tunnel)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bind($queue, $tunnel, $routeKey = '')
    {
    }

    /**
     * {@inheritdoc}
     */
    public function unbind($queue, $tunnel, $routeKey = '')
    {
    }
}


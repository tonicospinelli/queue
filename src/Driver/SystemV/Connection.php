<?php

namespace Queue\Driver\SystemV;

use Driver\SystemV\SystemVExchange;
use Queue\ConfigurationInterface;
use Queue\ConsumerInterface;
use Queue\Driver\MessageInterface;
use Queue\Exception\NotImplementedException;
use Queue\InterfaceQueue;
use Queue\ProducerInterface;

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
    public function publish(MessageInterface $message, ProducerInterface $producer)
    {
        $connection = $this->getQueue($producer);
        msg_send($connection, 1, $message->getBody());
    }

    /**
     * @param InterfaceQueue $queue
     * @return resource
     */
    private function getQueue(InterfaceQueue $queue)
    {
        $this->declareQueue($queue);
        return $this->connection[$queue->getWorkingQueueName()];
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(ConsumerInterface $consumer)
    {
        $connection = $this->getQueue($consumer);
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
    public function getExchange()
    {
        return new SystemVExchange();
    }

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function ack(MessageInterface $message)
    {
    }

    /**
     * @param InterfaceQueue $queue
     * @return void
     */
    public function declareQueue(InterfaceQueue $queue)
    {
        if (!isset($this->connection[$queue->getWorkingQueueName()])) {
            $this->connection[$queue->getWorkingQueueName()] = msg_get_queue(rand());
        }
    }

    /**
     *
     * @param MessageInterface $message
     * @return void
     */
    public function nack(MessageInterface $message)
    {
    }
}

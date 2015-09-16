<?php

namespace Queue\Driver\InMemory;

use Driver\InMemory\InMemoryExchange;
use Queue\ConfigurationInterface;
use Queue\ConsumerInterface;
use Queue\Driver\MessageInterface;
use Queue\Exception\NotImplementedException;
use Queue\ProducerInterface;
use Queue\Migration\Entity\AbstractBind as BindEntity;
use Queue\Migration\Entity\AbstractExchange as ExchangeEntity;
use Queue\Migration\Entity\AbstractQueue as QueueEntity;

class Connection implements \Queue\Driver\Connection
{
    /**
     * @var array
     */
    protected $connection;

    public function __construct(ConfigurationInterface $configuration)
    {}

    /**
     * {@inheritdoc}
     */
    public function close()
    {}

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
        $connection = $this->getConnection($producer->getWorkingQueueName());
        msg_send($connection, 1 , $message->getBody());
    }

    private function getConnection($queue)
    {
        if( ! isset($this->connection[$queue])) {
            $this->connection[$queue] = msg_get_queue(rand());
        }
        return $this->connection[$queue];
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(ConsumerInterface $consumer)
    {
        $connection = $this->getConnection($consumer->getWorkingQueueName());
        $msg_type = NULL;
        $msg = NULL;
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
        return new InMemoryExchange();
    }

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function ack(MessageInterface $message)
    {
    }

    /**
     *
     * @param MessageInterface $message
     * @return void
     */
    public function nack(MessageInterface $message)
    {
    }



    /**
     * @param QueueEntity $queue
     * @return void
     */
    public function createQueue(QueueEntity $queue)
    {
        // TODO: Implement createQueue() method.
    }

    /**
     * @param QueueEntity $queue
     * @return void
     */
    public function dropQueue(QueueEntity $queue)
    {
        // TODO: Implement dropQueue() method.
    }

    /**
     * @param ExchangeEntity $queue
     * @return void
     */
    public function createExchange(ExchangeEntity $queue)
    {
        // TODO: Implement createExchange() method.
    }

    /**
     * @param ExchangeEntity $queue
     * @return void
     */
    public function dropExchange(ExchangeEntity $queue)
    {
        // TODO: Implement dropExchange() method.
    }

    /**
     * @param BindEntity $queue
     * @return void
     */
    public function createBind(BindEntity $queue)
    {
        // TODO: Implement createBind() method.
    }

    /**
     * @param BindEntity $queue
     * @return void
     */
    public function dropBind(BindEntity $queue)
    {
        // TODO: Implement dropBind() method.
    }
}

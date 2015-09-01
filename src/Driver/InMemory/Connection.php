<?php

namespace Queue\Driver\InMemory;

use Driver\InMemory\InMemoryExchange;
use Queue\ConfigurationInterface;
use Queue\ConsumerInterface;
use Queue\Driver\MessageInterface;
use Queue\ProducerInterface;

class Connection implements \Queue\Driver\Connection
{
    /**
     * @var array
     */
    protected $connection;

    public function __construct(ConfigurationInterface $configuration)
    {}

    /**
     * @return void
     */
    public function close()
    {}

    /**
     * @param string $message
     * @param array $properties
     * @return MessageInterface
     */
    public function prepare($message, array $properties = array())
    {
        return new Message($message, $properties);
    }

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
     * @param ConsumerInterface $consumer
     * @return MessageInterface|null
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
     * @return InMemoryExchange
     */
    public function getExchange()
    {
        return new InMemoryExchange();
    }
}

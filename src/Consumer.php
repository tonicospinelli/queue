<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Resources\MessageInterface;
use Queue\Exception\RetryQueueException;
use Queue\Resources\QueueInterface;

abstract class Consumer extends AbstractProcess implements ConsumerInterface
{
    /**
     * @var bool
     */
    private $persistent;

    /**
     * @var QueueInterface
     */
    private $queue;

    public function __construct(DriverConnection $connection, QueueInterface $queue, $persistent = self::NOT_PERSISTENT)
    {
        parent::__construct($connection);
        $this->persistent = $persistent;
        $this->queue = $queue;
    }

    /**
     * {@inheritdoc}
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    final public function consume()
    {
        while (($message = $this->getConnection()->fetchOne($this->getQueue())) !== null || $this->isPersistent()) {
            if ($message instanceof MessageInterface) {
                try {
                    $this->process($message);
                    $this->handleProcessMessage($message);
                } catch (RetryQueueException $retryQueue) {
                    $this->handleProcessMessage($message);
                    $producer = new ProducerRetry($this->getConnection(), $retryQueue->getExchange());
                    $producer->publish($message);
                }
            }
        }
    }

    /**
     * @param MessageInterface $message
     */
    private function handleProcessMessage(MessageInterface $message)
    {
        switch (true) {
            case !$message->isAck():
                $this->getConnection()->nack($message);
                break;
            default:
                $this->getConnection()->ack($message);
        }
    }
}

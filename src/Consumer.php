<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Driver\MessageInterface;
use Queue\Exception\RetryQueueException;

abstract class Consumer extends AbstractProcess implements ConsumerInterface
{
    /**
     * @var bool
     */
    private $persistent;

    public function __construct(DriverConnection $connection, $persistent = self::NOT_PERSISTENT)
    {
        parent::__construct($connection);
        $this->persistent = $persistent;
    }

    /**
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * @return void
     */
    final public function consume()
    {
        while (($message = $this->getConnection()->fetchOne($this->queue())) !== null || $this->isPersistent()) {
            if ($message instanceof MessageInterface) {
                try {
                    $this->process($message);
                    $this->handleProcessMessage($message);
                } catch (RetryQueueException $retryQueue) {
                    $this->handleProcessMessage($message);
                    $producer = new ProducerRetry($this->getConnection(), $retryQueue->getExchange());
                    $message->renewTimeToLive();
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
            case $message->isNotAck():
                $this->getConnection()->nack($message);
                break;
            default:
                $this->getConnection()->ack($message);
        }
    }
}

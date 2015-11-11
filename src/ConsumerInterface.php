<?php

namespace Queue;

use Queue\Exception\RetryQueueException;
use Queue\Resources\MessageInterface;
use Queue\Resources\QueueInterface;

interface ConsumerInterface
{
    const PERSISTENT = true;
    const NOT_PERSISTENT = false;

    /**
     * @return QueueInterface
     */
    public function getQueue();

    /**
     * @return bool
     */
    public function isPersistent();

    /**
     * @param MessageInterface $message
     * @throws RetryQueueException
     * @return void
     */
    public function process(MessageInterface $message);
}

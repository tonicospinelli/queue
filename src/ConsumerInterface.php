<?php

namespace Queue;

use Exception\RetryQueueException;
use Queue\Driver\MessageInterface;

interface ConsumerInterface extends InterfaceQueue
{
    const PERSISTENT = true;
    const NOT_PERSISTENT = false;

    /**
     * @return void
     */
    public function consume();

    /**
     * @param MessageInterface $message
     * @throws RetryQueueException
     * @return void
     */
    public function process(MessageInterface $message);
}

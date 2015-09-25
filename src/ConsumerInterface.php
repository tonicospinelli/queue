<?php

namespace Queue;

use Queue\Entity\AbstractQueue;
use Queue\Exception\RetryQueueException;
use Queue\Driver\MessageInterface;

interface ConsumerInterface
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


    /**
     * @return AbstractQueue
     */
    public function queue();
}

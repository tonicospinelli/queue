<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace QueueTest\Mocks\Consumer;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use Queue\Entity\AbstractQueue;
use Queue\Exception\RetryQueueException;
use Queue\ExchangeInterface;
use QueueTest\Mocks\Entity\QueueEntityFake;

class ConsumerMockWithRetry extends Consumer
{
    /**
     * @param MessageInterface $message
     * @throws RetryQueueException
     * @return void
     */
    public function process(MessageInterface $message)
    {
        throw new RetryQueueException();
    }

    /**
     * @return AbstractQueue
     */
    public function queue()
    {
        return new QueueEntityFake();
    }
}
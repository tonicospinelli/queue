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
use QueueTest\Mocks\Entity\ExchangeRetryEntity;
use QueueTest\Mocks\Entity\QueueEntity;

class ConsumerMockWithRetry extends Consumer
{
    /**
     * @param MessageInterface $message
     * @throws RetryQueueException
     * @return void
     */
    public function process(MessageInterface $message)
    {
        throw new RetryQueueException(new ExchangeRetryEntity());
    }

    /**
     * @return AbstractQueue
     */
    public function queue()
    {
        return new QueueEntity();
    }
}

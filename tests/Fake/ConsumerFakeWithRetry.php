<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace QueueTest\Fake;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use Queue\Exception\RetryQueueException;

class ConsumerFakeWithRetry extends Consumer
{

    public function getWorkingQueueName()
    {
        return 'test.queue.fake';
    }

    public function getWorkingExchangeName()
    {
        return $this->getWorkingQueueName();
    }

    /**
     * @param MessageInterface $message
     * @throws RetryQueueException
     * @return void
     */
    public function process(MessageInterface $message)
    {
        throw new RetryQueueException();
    }
}
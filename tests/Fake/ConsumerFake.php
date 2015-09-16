<?php

namespace QueueTest\Fake;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use Queue\Exception\QueueException;
use Queue\ExchangeInterface;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */
class ConsumerFake extends Consumer
{

    public function getWorkingQueueName()
    {
        return 'test.queue.fake';
    }

    public function getWorkingExchangeName()
    {
        return 'test.queue.fake';
    }

    /**
     * {@inheritdoc}
     */
    public function process(MessageInterface $message)
    {
        echo $message->getBody();
        $message->setAck();
    }

    public function getExchange()
    {
        $exchange = parent::getExchange();
        $exchange->setAutoDelete(ExchangeInterface::AMQP_AUTO_DELETE_FALSE);
        return $exchange;
    }
}
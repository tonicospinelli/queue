<?php

namespace QueueTest\Fake;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use Queue\Entity\InterfaceQueue;
use Queue\Exception\QueueException;
use Queue\ExchangeInterface;
use QueueTest\Mocks\Entity\QueueEntityFake;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */
class ConsumerFake extends Consumer
{
    /**
     * {@inheritdoc}
     */
    public function process(MessageInterface $message)
    {
        echo $message->getBody();
        $message->setAck();
    }


    /**
     * @return InterfaceQueue
     */
    public function queue()
    {
        return new QueueEntityFake();
    }
}
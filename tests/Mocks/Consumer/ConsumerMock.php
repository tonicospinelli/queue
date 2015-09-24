<?php

namespace QueueTest\Mocks\Consumer;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use Queue\Entity\InterfaceQueue;
use Queue\Exception\QueueException;
use Queue\ExchangeInterface;
use QueueTest\Mocks\Entity\QueueEntity;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */
class ConsumerMock extends Consumer
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
        return new QueueEntity();
    }
}
<?php

namespace QueueTest\Mocks\Consumer;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use QueueTest\Mocks\Entity\QueueEntityFake;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */
class ConsumerFakeWithNoAck extends Consumer
{
    /**
     * {@inheritdoc}
     */
    public function process(MessageInterface $message)
    {
        echo $message->getBody();
        $message->setNotAck();
    }

    /**
     * {@inheritdoc}
     */
    public function queue()
    {
        return new QueueEntityFake();
    }
}
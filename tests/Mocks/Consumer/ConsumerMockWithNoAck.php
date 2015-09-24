<?php

namespace QueueTest\Mocks\Consumer;

use Queue\Consumer;
use Queue\Driver\MessageInterface;
use QueueTest\Mocks\Entity\QueueEntity;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */
class ConsumerMockWithNoAck extends Consumer
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
        return new QueueEntity();
    }
}
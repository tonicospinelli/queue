<?php

namespace QueueTest\Mocks\Consumer;

use Queue\Consumer;
use Queue\Resources\MessageInterface;

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
        echo $message->getBody() . PHP_EOL;
        $message->setAck();
    }
}
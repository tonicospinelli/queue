<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Mocks\Consumer;


use Queue\Consumer;
use Queue\Driver\MessageInterface;
use QueueTest\Mocks\Entity\QueueEntity;

class ConsumerMockMessage extends Consumer
{
    /**
     * {@inheritdoc}
     */
    public function process(MessageInterface $message)
    {
        echo $message->getBody();
        $message->setAck();
        $message->setNotAck();
        $message->setRequeue();
        $message->setRequeue(false);
        $message->getId();
        $message->getProperties();
        $message->isAck();
        $message->isNotAck();
        $message->isRequeue();

    }

    /**
     * {@inheritdoc}
     */
    public function queue()
    {
        return new QueueEntity();
    }


} 
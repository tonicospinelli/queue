<?php

namespace QueueTest\Mocks\Producer;

use Queue\Driver\MessageInterface;
use Queue\Entity\InterfaceExchange;
use Queue\Producer;
use QueueTest\Mocks\Entity\ExchangeEntity;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

class ProducerMock extends Producer
{
    /**
     * @return InterfaceExchange
     */
    public function exchange()
    {
        return new ExchangeEntity();
    }

    /**
     * @param string $message
     * @return MessageInterface
     */
    public function prepare($message)
    {
        $message = parent::prepare($message);
        $message->setRoutingKey('');
        return $message;
    }
}

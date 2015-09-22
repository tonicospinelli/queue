<?php

namespace QueueTest\Fake;

use Queue\Entity\InterfaceExchange;
use Queue\Producer;
use QueueTest\Mocks\Entity\ExchangeEntityFake;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

class ProducerFake extends Producer
{
    /**
     * @return InterfaceExchange
     */
    public function exchange()
    {
        return new ExchangeEntityFake();
    }

}
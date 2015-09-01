<?php

namespace QueueTest\Fake;

use Queue\ExchangeInterface;
use Queue\Producer;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

class ProducerFake extends Producer
{

    public function getWorkingQueueName()
    {
        return 'test.queue';
    }

    public function getWorkingExchangeName()
    {
        return 'amqp.direct';
    }

    public function getExchange()
    {
        $exchange = parent::getExchange();
        $exchange->setAutoDelete(ExchangeInterface::AMQP_AUTO_DELETE_TRUE);
        return $exchange;
    }


}
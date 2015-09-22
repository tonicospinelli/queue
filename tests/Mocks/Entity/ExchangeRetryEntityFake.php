<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


use Queue\Entity\AbstractExchange;

class ExchangeRetryEntityFake extends AbstractExchange
{
    public function getExchangeName()
    {
        return 'migration.queue.test.retry';
    }

    public function getType()
    {
        return self::TYPE_DIRECT;
    }
}
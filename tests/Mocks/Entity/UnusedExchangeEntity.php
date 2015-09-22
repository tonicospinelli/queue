<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Mocks\Entity;


use Queue\Entity\AbstractExchange;

class UnusedExchangeEntity extends AbstractExchange
{
    public function getExchangeName()
    {
        return 'queue.test.exchange.unused';
    }

    public function getType()
    {
        return self::TYPE_DIRECT;
    }
} 
<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


use Queue\Entity\AbstractExchange;

class ExchangeEntity extends AbstractExchange
{
    /**
     * {@inheritdoc}
     */
    public function getExchangeName()
    {
        return 'migration.queue.test';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE_FANOUT;
    }
}
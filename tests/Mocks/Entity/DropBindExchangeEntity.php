<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;

use Queue\Entity\AbstractBindExchange;

class DropBindExchangeEntity extends AbstractBindExchange
{

    protected $invalid = true;

    /**
     * {@inheritdoc}
     */
    public function getRoutingKey()
    {
        return 'fake.routing.key.unbind';
    }

    /**
     * {@inheritdoc}
     */
    public function getExchange()
    {
        return new ExchangeEntity();
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetExchange()
    {
        return new TargetExchangeEntity();
    }
}
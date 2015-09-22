<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Mocks\Entity;


use Queue\Entity\AbstractBindExchange;
use Queue\Entity\AbstractExchange;

class UnusedExchangeBind extends AbstractBindExchange
{

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return '*';
    }

    /**
     * @return AbstractExchange
     */
    public function getExchange()
    {
        return new UnusedExchangeEntity();
    }

    /**
     * @return AbstractExchange
     */
    public function getTargetExchange()
    {
        return new UnusedExchangeEntity();
    }
}
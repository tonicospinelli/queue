<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Mocks\Entity;

use Queue\Entity\AbstractBindQueue;
use Queue\Entity\AbstractExchange;

class UnusedQueueBind extends AbstractBindQueue
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
    public function getTargetQueue()
    {
        return new UnusedQueueEntity();
    }
}
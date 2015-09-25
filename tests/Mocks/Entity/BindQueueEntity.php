<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;

use Queue\Entity\AbstractBindQueue;
use Queue\Entity\AbstractQueue;

class BindQueueEntity extends AbstractBindQueue
{

    /**
     * {@inheritdoc}
     */
    public function getRoutingKey()
    {
        return 'fake.routing.key';
    }

    /**
     * {@inheritdoc}
     */
    public function getExchange()
    {
        return new ExchangeEntity();
    }

    /**
     * @return AbstractQueue
     */
    public function getTargetQueue()
    {
        return new QueueEntity();
    }

}
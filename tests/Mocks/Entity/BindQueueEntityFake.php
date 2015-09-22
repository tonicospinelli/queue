<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;

use Queue\Entity\AbstractBindQueue;
use Queue\Entity\AbstractQueue;

class BindQueueEntityFake extends AbstractBindQueue
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
        return new ExchangeEntityFake();
    }

    /**
     * @return AbstractQueue
     */
    public function getTargetQueue()
    {
        return new QueueEntityFake();
    }

}
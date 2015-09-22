<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Mocks\Entity;


use Queue\Entity\AbstractQueue;

class UnusedQueueEntity extends AbstractQueue
{
    public function getQueueName()
    {
        return 'queue.test.queue.unused';
    }
} 
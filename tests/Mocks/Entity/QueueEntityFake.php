<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


use Queue\Entity\AbstractQueue;

class QueueEntityFake extends AbstractQueue
{
    protected $queueArguments = array(
        'x-message-ttl' => array('I', 12000),
        'x-dead-letter-exchange' => array('S', 'test.queue.fake'),
    );

    public function getQueueName()
    {
        return 'migration.queue.test';
    }
}
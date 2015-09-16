<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Fake\Migration;


use Queue\Migration\Entity\AbstractQueue;

class QueueMigrationFake extends AbstractQueue
{

    public function getQueueName()
    {
        return 'migration.queue.test';
    }

    public function getQueueArguments()
    {
        return array(
            'x-message-ttl' => array('I', 10000),
            'x-dead-letter-exchange' => array('S', 'test.queue.fake'),
        );
    }
}
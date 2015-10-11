<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Unit\Driver;

use Queue\Configuration;
use Queue\Driver;
use Queue\Resources\Amqp\Queue;
use Queue\Resources\Amqp\Tunnel;

class AmqpTest extends TestCase
{
    /**
     * @return \Queue\Driver\Connection
     */
    public function createConnection()
    {
        return new Driver\Amqp\Connection(
            new Configuration(
                Driver::AMQP,
                '33.33.33.1',
                5672,
                'kanui',
                'kanui',
                array('no_wait', true)
            )
        );
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $attributes
     * @return \Queue\Resources\Tunnel
     */
    public function createTunnel($name, $type, array $attributes = array())
    {
        $tunnel = new Tunnel($name, $type, $attributes);
        $tunnel->setDurable();
        $tunnel->setAutoDelete(false);
        return $tunnel;
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return Queue
     */
    public function createQueue($name, array $attributes = array())
    {
        $queue = new Queue($name, $attributes);
        $queue->setDurable();
        $queue->setAutoDelete(false);
        return $queue;
    }
}

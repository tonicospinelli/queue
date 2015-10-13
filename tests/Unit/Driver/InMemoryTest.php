<?php

namespace QueueTest\Unit\Driver;

use Queue\Configuration;
use Queue\Driver;
use Queue\Driver\InMemory\Connection;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

class InMemoryTest extends TestCase
{
    /**
     * @return \Queue\Driver\Connection
     */
    public function createConnection()
    {
        return new Connection(new Configuration(Driver::IN_MEMORY));
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $attributes
     * @return Tunnel
     */
    public function createTunnel($name, $type, array $attributes = array())
    {
        $tunnel = new Tunnel($name, $type, $attributes);
        $tunnel->addRoute(self::QUEUE_NAME, self::ROUTE_NAME);
        return $tunnel;
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return Queue
     */
    public function createQueue($name, array $attributes = array())
    {
        return new Queue($name, $attributes);
    }
}

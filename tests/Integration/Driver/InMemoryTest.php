<?php

namespace QueueTest\Integration\Driver;

use Queue\Configuration;
use Queue\Driver as BaseDriver;
use Queue\Driver\InMemory\Connection;
use Queue\Resources\Queue;
use Queue\Resources\Exchange;

class InMemoryTest extends TestCase
{
    /**
     * @return \Queue\Driver\Connection
     */
    public function createConnection()
    {
        return new Connection(new Configuration(BaseDriver::IN_MEMORY), new BaseDriver\InMemory\Driver());
    }
}

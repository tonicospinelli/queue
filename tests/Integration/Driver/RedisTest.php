<?php

namespace QueueTest\Integration\Driver;

use Queue\Configuration;
use Queue\Driver as BaseDriver;

class RedisTest extends TestCase
{
    /**
     * @return \Queue\Driver\Connection
     */
    public function createConnection()
    {
        return new BaseDriver\Redis\Connection(
            new Configuration(
                BaseDriver::REDIS,
                getenv('REDIS_HOST'),
                getenv('REDIS_PORT'),
                null,
                null
            ),
            new BaseDriver\Redis\Driver()
        );
    }
}

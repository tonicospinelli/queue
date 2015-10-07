<?php

namespace QueueTest\Unit\Component;

use Queue\Component\Setup;
use Queue\Configuration;
use Queue\Driver;

class SetupTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateYAMLResource()
    {
        $resourceManager = Setup::createYAMLResource(__DIR__, new Configuration(Driver::AMQP));

        $this->assertInstanceOf('Queue\Resources\Queue', $resourceManager->getQueue('log.error'));
        $this->assertInstanceOf('Queue\Resources\Queue', $resourceManager->getQueue('log.something'));

        $tunnel = $resourceManager->getTunnel('log');
        $this->assertInstanceOf('Queue\Resources\Tunnel', $tunnel);
        foreach ($tunnel->getBindings() as $binding) {
            $this->assertInstanceOf('Queue\Resources\Binding', $binding);
        }
    }
}

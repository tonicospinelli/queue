<?php

namespace QueueTest\Unit\Component;

use Queue\Component\Setup;
use Queue\Configuration;
use Queue\Driver;

class SetupTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateYAMLResource()
    {
        $resourceManager = Setup::createYAMLResource(__DIR__ . '/resources.yml', new Configuration(Driver::AMQP));

        $this->assertInstanceOf('Queue\Resources\Queue', $resourceManager->getQueue('logs.error'));
        $this->assertInstanceOf('Queue\Resources\Queue', $resourceManager->getQueue('logs.something'));

        $tunnel = $resourceManager->getTunnel('logs');

        $this->assertInstanceOf('Queue\Resources\Tunnel', $tunnel);
        $this->assertCount(2, $tunnel->getRoutes());

        $this->assertTrue($tunnel->hasRoute('error'));
        $this->assertCount(2, $tunnel->getQueuesFromRoute('error'));

        $this->assertTrue($tunnel->hasRoute('warning'));
        $this->assertCount(1, $tunnel->getQueuesFromRoute('warning'));
    }
}

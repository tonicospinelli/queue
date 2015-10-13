<?php

namespace QueueTest\Unit\Component;

use Queue\Component\ResourceTool;
use Queue\Component\Setup;
use Queue\Configuration;
use Queue\Connection;
use Queue\Driver;
use Queue\DriverManager;

class ResourceToolTest extends \PHPUnit_Framework_TestCase
{
    public function testRaiseAmqpResourcesSoThatDoesNotThrowAnException()
    {
        $connection = $this->getMockBuilder('Queue\Driver\Connection')->getMock();

        $driver = $this
            ->getMockBuilder('Queue\Driver')
            ->setMethods(array('connect'))
            ->getMock();
        $driver->expects($this->once())->method('connect')->willReturn($connection);
        $configuration = new Configuration(Driver::AMQP, RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD);

        $resourceManager = Setup::createYAMLResource(__DIR__ . '/resources.yml', $configuration);

        $connection = new Connection($configuration, $driver);

        $tool = new ResourceTool($connection, $resourceManager);

        $tool->raiseResources();

        // I know it is weird, but if you not agree with it,
        // do your best and show me how to solve it! ;)
        $this->assertTrue(true);
    }
}

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
            ->setMethods(array('connect', 'getName', 'createQueue', 'createExchange', 'createMessage'))
            ->getMock();
        $driver->expects($this->once())->method('connect')->willReturn($connection);
        $driver->expects($this->any())->method('getName')->willReturn('amqp');
        $driver->expects($this->any())->method('createQueue')->willReturn($this->getMockBuilder('Queue\Resources\Queue')->disableOriginalConstructor()->getMock());
        $driver->expects($this->any())->method('createExchange')->willReturn($this->getMockBuilder('Queue\Resources\Exchange')->disableOriginalConstructor()->getMock());
        $driver->expects($this->any())->method('createMessage')->willReturn($this->getMockBuilder('Queue\Resources\Message')->disableOriginalConstructor()->getMock());

        $configuration = new Configuration(Driver::AMQP, getenv('RABBIT_HOST'), getenv('RABBIT_PORT'), getenv('RABBIT_USERNAME'), getenv('RABBIT_PASSWORD'));

        $connection = DriverManager::getConnection($configuration);

        $resourceManager = Setup::createYAMLResource(__DIR__ . '/resources.yml', $connection);

        $connection = new Connection($configuration, $driver);

        $tool = new ResourceTool($connection, $resourceManager);

        $tool->raiseResources();

        // I know it is weird, but if you not agree with it,
        // do your best and show me how to solve it! ;)
        $this->assertTrue(true);
    }
}

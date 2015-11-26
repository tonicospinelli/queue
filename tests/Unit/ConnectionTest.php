<?php

namespace QueueTest\Unit;

use Queue\Connection as BaseConnection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Queue\Configuration
     */
    private function getMockedConfiguration()
    {
        return $configuration = $this->getMockBuilder('Queue\Configuration')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param string $method
     * @return \PHPUnit_Framework_MockObject_MockObject|\Queue\Driver
     */
    private function getMockedDriver($method)
    {
        $driverConnection = $this->getMockForAbstractClass('Queue\Driver\Connection');
        $driverConnection->expects($this->once())->method($method)->willReturn(null);

        $mock = $this->getMockForAbstractClass('Queue\Driver', array('connect', $method));
        $mock->expects($this->once())->method('connect')->willReturn($driverConnection);

        return $mock;
    }

    public function testCreateQueue()
    {
        $driver = $this->getMockedDriver('createQueue');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->createQueue($this->getMock('Queue\Resources\QueueInterface'));
    }

    public function testDeleteQueue()
    {
        $driver = $this->getMockedDriver('deleteQueue');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->deleteQueue($this->getMock('Queue\Resources\QueueInterface'));
    }

    public function testCreateExchange()
    {
        $driver = $this->getMockedDriver('createExchange');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->createExchange($this->getMock('Queue\Resources\ExchangeInterface'));
    }

    public function testDeleteExchange()
    {
        $driver = $this->getMockedDriver('deleteExchange');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->deleteExchange($this->getMock('Queue\Resources\ExchangeInterface'));
    }

    public function testBind()
    {
        $driver = $this->getMockedDriver('bind');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->bind($this->getMock('Queue\Resources\QueueInterface'), $this->getMock('Queue\Resources\ExchangeInterface'));
    }

    public function testUnbind()
    {
        $driver = $this->getMockedDriver('unbind');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->unbind($this->getMock('Queue\Resources\QueueInterface'), $this->getMock('Queue\Resources\ExchangeInterface'));
    }

    public function testClose()
    {
        $driver = $this->getMockedDriver('close');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->close();
    }

    public function testAck()
    {
        $driver = $this->getMockedDriver('ack');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->ack($this->getMock('Queue\Resources\MessageInterface'));
    }

    public function testNack()
    {
        $driver = $this->getMockedDriver('nack');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->nack($this->getMock('Queue\Resources\MessageInterface'));
    }

    public function testPublish()
    {
        $driver = $this->getMockedDriver('publish');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->publish($this->getMock('Queue\Resources\MessageInterface'), $this->getMock('Queue\Resources\ExchangeInterface'));
    }

    public function testFetchOne()
    {
        $driver = $this->getMockedDriver('fetchOne');

        $connection = new BaseConnection($this->getMockedConfiguration(), $driver);
        $connection->fetchOne($this->getMock('Queue\Resources\QueueInterface'));
    }
}
 
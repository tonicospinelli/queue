<?php

namespace QueueTest\Unit;

use Queue\Resources\MessageInterface;

class ProducerTest extends \PHPUnit_Framework_TestCase
{
    public function testIShouldCreateAnObject()
    {
        $exchange = $this->getMock('Queue\Resources\ExchangeInterface');
        $connection = $this->getMock('Queue\Driver\Connection');

        $producer = $this->getMockBuilder('Queue\Producer')
            ->setConstructorArgs(array($connection, $exchange))
            ->getMock();

        $this->assertInstanceOf('\Queue\Producer', $producer);
    }

    public function testIWantToPrepareMessage()
    {

        $exchange = $this->getMock('Queue\Resources\ExchangeInterface');

        $driver = $this->getMockForAbstractClass(
            'Queue\Driver', array(), '', false, true, true, array('createMessage')
        );
        $driver->expects($this->once())->method('createMessage')->willReturnCallback(function ($message) {
            return $this->getMock('\Queue\Resources\Message', null, array($message));
        });

        $connection = $this->getMockForAbstractClass(
            'Queue\Driver\Connection', array(), '', false, true, true, array('getDriver')
        );
        $connection->expects($this->once())->method('getDriver')->willReturn($driver);

        /** @var \Queue\ProducerInterface $producer */
        $producer = $this->getMockForAbstractClass('\Queue\Producer', array($connection, $exchange));

        $preparedMessage = $producer->prepare('my message');
        $this->assertInstanceOf('\Queue\Resources\MessageInterface', $preparedMessage);
        $this->assertEquals('my message', $preparedMessage->getBody());

        return $preparedMessage;
    }

    /**
     * @depends testIWantToPrepareMessage
     * @param MessageInterface $message
     */
    public function testIWantToPublishAMessage(MessageInterface $message)
    {
        $exchange = $this->getMock('Queue\Resources\ExchangeInterface');;

        $connection = $this->getMock('Queue\Driver\Connection');

        /** @var \Queue\ProducerInterface $producer */
        $producer = $this->getMockForAbstractClass('Queue\Producer', array($connection, $exchange));

        $producer->publish($message);
    }
}
 
<?php

namespace QueueTest\Unit;

use Queue\Consumer;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    private static $fistTryConsume = true;

    public function testIShouldCreateAnObject()
    {
        $queue = $this->getMock('Queue\Resources\QueueInterface');
        $connection = $this->getMock('Queue\Driver\Connection');

        $consumer = $this->getMockBuilder('Queue\Consumer')
            ->setConstructorArgs(array($connection, $queue))
            ->getMock();

        $this->assertInstanceOf('\Queue\Consumer', $consumer);
    }

    public function testIShouldConsumeAMessage()
    {
        $queue = $this->getMock('Queue\Resources\QueueInterface');
        $connection = $this->getMockForAbstractClass(
            'Queue\Driver\Connection',
            array(),
            '',
            false,
            true,
            true,
            array('fetchOne')
        );
        $connection->expects($this->any())->method('fetchOne')->willReturnCallback(function () {
            if (self::$fistTryConsume) {
                self::$fistTryConsume = false;
                $message = $this->getMockForAbstractClass(
                    'Queue\Resources\MessageInterface',
                    array(),
                    '',
                    true,
                    true,
                    true,
                    array('isAck')
                );
                $message->expects($this->once())->method('isAck')->willReturn(true);
                return $message;
            }
            return null;
        });

        /** @var Consumer $consumer */
        $consumer = $this->getMockForAbstractClass(
            'Queue\Consumer',
            array($connection, $queue),
            '',
            true,
            true,
            true,
            array('process')
        );
        $consumer->expects($this->once())->method('process');
        $consumer->consume();
    }
}
 
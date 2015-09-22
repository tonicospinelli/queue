<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Unit\Driver;



use QueueTest\Fake\ConnectionFake;
use QueueTest\Mocks\Entity\UnusedExchangeBind;
use QueueTest\Mocks\Entity\UnusedQueueBind;

class AmqpTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException \Queue\Driver\Amqp\AmqpException
     */
    public function testConnectionException()
    {
        $connect = ConnectionFake::amqp(true);
        $connect->close();
    }

    public function testBind()
    {
        $connect = ConnectionFake::amqp();
        $connect->createBind(new UnusedExchangeBind());
        $connect->createBind(new UnusedQueueBind());
    }

    public function testDropBind()
    {
        $connect = ConnectionFake::amqp();
        $connect->dropBind(new UnusedQueueBind());
        $connect->dropBind(new UnusedExchangeBind());
    }

    /**
     * @before testBind
     */
    public function dropExchangeAndQueue()
    {
        $connect = ConnectionFake::amqp();
        $connect->dropExchange((new UnusedQueueBind())->getExchange());
        $connect->dropQueue((new UnusedQueueBind())->getTargetQueue());
    }
}

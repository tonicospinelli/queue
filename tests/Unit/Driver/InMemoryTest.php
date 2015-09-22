<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Unit\Driver;



use QueueTest\Fake\ConnectionFake;
use QueueTest\Mocks\Entity\ExchangeEntityFake;
use QueueTest\Mocks\Entity\QueueEntityFake;

class InMemoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException \Queue\Driver\InMemory\InMemoryException
     */
    public function testConnectionException()
    {
        $connect = ConnectionFake::inMemory(true);
        $connect->close();
    }

    public function testDropQueue()
    {
        $connect = ConnectionFake::inMemory();
        $connect->dropQueue(new QueueEntityFake());
    }

    public function testDropExchange()
    {
        $connect = ConnectionFake::inMemory();
        $connect->dropExchange(new ExchangeEntityFake());
    }
}

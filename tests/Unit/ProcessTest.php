<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Unit;


use Queue\Driver\Connection;
use QueueTest\Fake\ConnectionFake;
use QueueTest\Fake\ConsumerFake;
use QueueTest\Fake\ConsumerFakeWithRetry;
use QueueTest\Mocks\Producer\ProducerMock;
use QueueTest\Mocks\Consumer\ConsumerFakeMessage;
use QueueTest\Mocks\Consumer\ConsumerFakeWithNoAck;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testProducerAndConsumerFlux(Connection $connection)
    {
        $messageTxt = 'testMessage';
        $producer = new ProducerMock($connection);
        $message = $producer->prepare($messageTxt);
        $producer->publish($message);

        $consumer = new ConsumerFake($connection, ConsumerFake::NOT_PERSISTENT);
        $consumer->consume();
        $this->expectOutputString($messageTxt);
    }

    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testConsumerNoAckFlux(Connection $connection)
    {
        $messageTxt = 'testMessageWithNoAck';
        $producer = new ProducerMock($connection);
        $message = $producer->prepare($messageTxt);
        $producer->publish($message);

        $consumer = new ConsumerFakeWithNoAck($connection, ConsumerFake::NOT_PERSISTENT);
        $consumer->consume();
        $this->expectOutputString($messageTxt);
    }


    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testConsumerRetryFlux(Connection $connection)
    {
        $producer = new ProducerMock($connection);
        $message = $producer->prepare('');
        $producer->publish($message);

        $consumer = new ConsumerFakeWithRetry($connection, ConsumerFake::NOT_PERSISTENT);
        $consumer->consume();
    }

    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testMessageAccessor(Connection $connection)
    {
        $producer = new ProducerMock($connection);
        $message = $producer->prepare('');
        $producer->publish($message);

        $consumer = new ConsumerFakeMessage($connection, ConsumerFake::NOT_PERSISTENT);
        $consumer->consume();
    }

    public function connectionProvider()
    {
        return ConnectionFake::providerAll();
    }
}
 
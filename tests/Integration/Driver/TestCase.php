<?php

namespace QueueTest\Integration\Driver;

use Queue\Driver\Connection;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Exchange;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    const QUEUE_NAME = 'test.logs.error';
    const EXCHANGE_NAME = 'test.logs';
    const EXCHANGE_TYPE = Exchange::TYPE_DIRECT;
    const ROUTE_NAME = '';
    /**
     * @var Connection
     */
    private static $connection;

    /**
     * @return Connection
     */
    abstract public function createConnection();

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        if (self::$connection instanceof Connection) {
            self::$connection->close();
        }
    }

    /**
     * @return Connection
     */
    public function testIWantToConnectAtMessageQueueServer()
    {
        try {
            $connection = $this->createConnection();
            self::$connection = $connection;
            return $connection;
        } catch (\Exception $e) {
            $this->markTestSkipped('Cannot connect at Message Queue Server');
        }
    }

    /**
     * @depends testIWantToConnectAtMessageQueueServer
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToCreateALogErrorQueue(Connection $connection)
    {
        $queue = $this->createQueue($connection);
        $connection->createQueue($queue);
        return $connection;
    }

    /**
     * @depends testIWantToCreateALogErrorQueue
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToCreateALogExchange(Connection $connection)
    {
        $exchange = $this->createExchange($connection);
        $connection->createExchange($exchange);
        return $connection;
    }

    /**
     * @depends testIWantToCreateALogExchange
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToBindALogErrorQueueWithLogExchange(Connection $connection)
    {
        $queue = $this->createQueue($connection);
        $exchange = $this->createExchange($connection);
        $connection->bind($queue, $exchange, self::ROUTE_NAME);
        return $connection;
    }

    /**
     * @depends testIWantToBindALogErrorQueueWithLogExchange
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToPublishAMessageAtLogsExchange(Connection $connection)
    {
        $message = $connection->getDriver()->createMessage('some error message');

        $this->assertInstanceOf('Queue\Resources\MessageInterface', $message);

        $exchange = $this->createExchange($connection);

        $connection->publish($message, $exchange, self::ROUTE_NAME);

        return $connection;
    }

    /**
     * @depends testIWantToPublishAMessageAtLogsExchange
     * @param Connection $connection
     * @return array
     */
    public function testIWantToFetchASingleMessageFromLogErrorQueue(Connection $connection)
    {
        $queue = $this->createQueue($connection);
        $message = $connection->fetchOne($queue);
        $this->assertInstanceOf('Queue\Resources\MessageInterface', $message);
        $this->assertEquals('some error message', $message->getBody());
        return array(
            'connection' => $connection,
            'message' => $message
        );
    }

    /**
     * @depends testIWantToFetchASingleMessageFromLogErrorQueue
     * @param array $data
     * @return Connection
     */
    public function testIWantToNacknowledgeAndRequeueAMessageFromLogErrorQueue(array $data)
    {
        /** @var Connection $connection */
        $connection = $data['connection'];

        /** @var MessageInterface $message */
        $message = $data['message'];
        $message->setRequeue();

        $connection->nack($message);

        $queue = $this->createQueue($connection);
        $message = $connection->fetchOne($queue);

        $this->assertInstanceOf('Queue\Resources\MessageInterface', $message);

        return array(
            'connection' => $connection,
            'message' => $message
        );
    }

    /**
     * @depends testIWantToNacknowledgeAndRequeueAMessageFromLogErrorQueue
     * @param array $data
     * @return Connection
     */
    public function testIWantToAcknowledgeAMessageFromLogErrorQueue(array $data)
    {
        /** @var Connection $connection */
        $connection = $data['connection'];

        /** @var MessageInterface $message */
        $message = $data['message'];

        $connection->ack($message);

        $queue = $this->createQueue($connection);
        $message = $connection->fetchOne($queue);

        $this->assertNull($message);
        return $connection;
    }

    /**
     * @depends testIWantToAcknowledgeAMessageFromLogErrorQueue
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToUnbindALogErrorQueueWithLogExchange(Connection $connection)
    {
        $queue = $this->createQueue($connection);
        $exchange = $this->createExchange($connection);
        $connection->unbind($queue, $exchange, self::ROUTE_NAME);
        return $connection;
    }

    /**
     * @depends testIWantToUnbindALogErrorQueueWithLogExchange
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToDeleteALogErrorQueue(Connection $connection)
    {
        $queue = $this->createQueue($connection);
        $connection->deleteQueue($queue);
        return $connection;
    }

    /**
     * @depends testIWantToDeleteALogErrorQueue
     * @param Connection $connection
     */
    public function testIWantToDeleteALogExchange(Connection $connection)
    {
        $exchange = $this->createExchange($connection);
        $connection->deleteExchange($exchange);
    }

    /**
     * @param Connection $connection
     * @return \Queue\Resources\ExchangeInterface
     */
    protected function createExchange(Connection $connection)
    {
        $exchange = $connection->getDriver()->createExchange(self::EXCHANGE_NAME, self::EXCHANGE_TYPE);
        $exchange->addBinding(self::QUEUE_NAME, self::ROUTE_NAME);
        return $exchange;
    }

    /**
     * @param Connection $connection
     * @return \Queue\Resources\QueueInterface
     */
    protected function createQueue(Connection $connection)
    {
        $queue = $connection->getDriver()->createQueue(self::QUEUE_NAME);
        return $queue;
    }
}

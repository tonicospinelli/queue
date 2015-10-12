<?php

namespace QueueTest\Unit\Driver;

use Queue\Driver\Connection;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    const QUEUE_NAME = 'logs.error';
    const TUNNEL_NAME = 'logs';
    const TUNNEL_TYPE = Tunnel::TYPE_DIRECT;
    const ROUTE_NAME = 'error';
    /**
     * @var Connection
     */
    private static $connection;

    /**
     * @return Connection
     */
    abstract public function createConnection();

    /**
     * @param string $name
     * @param string $type
     * @param array $attributes
     * @return Tunnel
     */
    abstract public function createTunnel($name, $type, array $attributes = array());

    /**
     * @param string $name
     * @param array $attributes
     * @return Queue
     */
    abstract public function createQueue($name, array $attributes = array());


    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        self::$connection->close();
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
        $queue = $this->createQueue(self::QUEUE_NAME);
        $connection->createQueue($queue);
        return $connection;
    }

    /**
     * @depends testIWantToCreateALogErrorQueue
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToCreateALogTunnel(Connection $connection)
    {
        $tunnel = $this->createTunnel(self::TUNNEL_NAME, self::TUNNEL_TYPE);
        $connection->createTunnel($tunnel);
        return $connection;
    }

    /**
     * @depends testIWantToCreateALogTunnel
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToBindALogErrorQueueWithLogTunnel(Connection $connection)
    {
        $connection->bind(self::QUEUE_NAME, self::TUNNEL_NAME, self::ROUTE_NAME);
        return $connection;
    }

    /**
     * @depends testIWantToBindALogErrorQueueWithLogTunnel
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToPublishAMessageAtLogsTunnel(Connection $connection)
    {
        $message = $connection->prepare('some error message');

        $this->assertInstanceOf('Queue\Resources\MessageInterface', $message);

        $tunnel = $this->createTunnel(self::TUNNEL_NAME, Tunnel::TYPE_DIRECT);
        $connection->publish($message, $tunnel, self::ROUTE_NAME);

        return $connection;
    }

    /**
     * @depends testIWantToPublishAMessageAtLogsTunnel
     * @param Connection $connection
     * @return array
     */
    public function testIWantToFetchASingleMessageFromLogErrorQueue(Connection $connection)
    {
        $message = $connection->fetchOne('logs.error');
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

        $message = $connection->fetchOne(self::QUEUE_NAME);

        $this->assertInstanceOf('Queue\Resources\MessageInterface', $message);

        return array(
            'connection' => $connection,
            'message' => $message
        );
    }

    /**
     * @depends testIWantToNacknowledgeAndRequeueAMessageFromLogErrorQueue
     * @param array $data
     */
    public function testIWantToAcknowledgeAMessageFromLogErrorQueue(array $data)
    {
        /** @var Connection $connection */
        $connection = $data['connection'];

        /** @var MessageInterface $message */
        $message = $data['message'];

        $connection->ack($message);

        $message = $connection->fetchOne(self::QUEUE_NAME);

        $this->assertNull($message);
        return $connection;
    }

    /**
     * @depends testIWantToAcknowledgeAMessageFromLogErrorQueue
     */
    public function testIWantToUnbindALogErrorQueueWithLogTunnel(Connection $connection)
    {
        $connection->unbind(self::QUEUE_NAME, self::TUNNEL_NAME, self::ROUTE_NAME);
        return $connection;
    }

    /**
     * @depends testIWantToUnbindALogErrorQueueWithLogTunnel
     * @param Connection $connection
     * @return Connection
     */
    public function testIWantToDeleteALogErrorQueue(Connection $connection)
    {
        $queue = $this->createQueue(self::QUEUE_NAME);
        $connection->deleteQueue($queue);
        return $connection;
    }

    /**
     * @depends testIWantToDeleteALogErrorQueue
     * @param Connection $connection
     */
    public function testIWantToDeleteALogTunnel(Connection $connection)
    {
        $tunnel = $this->createTunnel(self::TUNNEL_NAME, self::TUNNEL_TYPE);
        $connection->deleteTunnel($tunnel);
    }
}

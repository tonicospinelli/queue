<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.01
 *
 */

namespace QueueTest\Unit;


use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;
use QueueTest\Fake\ConsumerFake;
use QueueTest\Fake\ProducerFake;

class ProducerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ProducerFake
     */
    protected $object;

    protected $connection;

    public function setUp()
    {
        $this->connection = DriverManager::getConnection(new Configuration(Driver::IN_MEMORY));
        $this->object = new ProducerFake($this->connection);
    }
    
    public function tearDown()
    {
        $this->object = null;
    }

    public function testProducer()
    {
        $messageTxt = 'rola';
        $message = $this->object->prepare($messageTxt);
        $this->object->publish($message);
        $consumer = new ConsumerFake($this->connection, ConsumerFake::NOT_PERSISTENT);
        $consumer->consume();
        $this->expectOutputString($messageTxt);
    }

    public function testProducerFanout()
    {
        $connection = DriverManager::getConnection(new Configuration(Driver::AMQP, RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD));
        $producer = new ProducerFake($connection);
        $message = $producer->prepare('test: '. rand() );
        $producer->publish($message);
    }

}
 
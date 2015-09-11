<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace QueueTest\Unit;


use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;
use QueueTest\Fake\ConsumerFakeWithRetry;

class ConsumerRetryTest extends \PHPUnit_Framework_TestCase
{
    public function testRetryQueue()
    {
        $connection = DriverManager::getConnection(new Configuration(Driver::AMQP, RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD));
        $consumer = new ConsumerFakeWithRetry($connection);
        $consumer->consume();
    }
}
 
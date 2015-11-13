<?php

namespace QueueTest\Unit\Component;

use Queue\Component\Setup;
use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;

class SetupTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateYAMLResource()
    {
        $configuration = new Configuration(Driver::AMQP, getenv('RABBIT_HOST'), getenv('RABBIT_PORT'), getenv('RABBIT_USERNAME'), getenv('RABBIT_PASSWORD'));

        $connection = DriverManager::getConnection($configuration);

        $resourceManager = Setup::createYAMLResource(__DIR__ . '/resources.yml', $connection);

        $this->assertInstanceOf('Queue\Resources\Amqp\QueueInterface', $resourceManager->getQueue('logs.error'));
        $this->assertInstanceOf('Queue\Resources\Amqp\QueueInterface', $resourceManager->getQueue('logs.something'));

        $exchange = $resourceManager->getExchange('logs');

        $this->assertInstanceOf('Queue\Resources\Amqp\ExchangeInterface', $exchange);
        $this->assertCount(2, $exchange->getBindings());

        $this->assertTrue($exchange->hasBinding('error'));
        $this->assertCount(2, $exchange->getQueuesFromBinding('error'));

        $this->assertTrue($exchange->hasBinding('warning'));
        $this->assertCount(1, $exchange->getQueuesFromBinding('warning'));
    }
}

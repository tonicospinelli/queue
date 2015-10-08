<?php

namespace QueueTest\Unit\Component;


use Queue\Component\ResourceTool;
use Queue\Component\Setup;
use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;

class ResourceToolTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateYAMLResource()
    {
        $connection = DriverManager::getConnection(
            new Configuration(Driver::AMQP, RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD)
        );
        $resourceManager = Setup::createYAMLResource(__DIR__ . '/resources.yml', $connection->getConfiguration());
        $tool = new ResourceTool($connection, $resourceManager);
        $tool->raiseResources();
    }
}

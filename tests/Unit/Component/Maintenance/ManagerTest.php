<?php

use Queue\Component\Maintenance\Manager;
use Queue\Driver\Connection;
use Queue\Entity\AbstractEntity;
use QueueTest\Fake\ConnectionFake;
use QueueTest\Mocks\Entity\BindExchangeEntityFake;
use QueueTest\Mocks\Entity\BindQueueEntityFake;
use QueueTest\Mocks\Entity\DivergentExchangeEntityFake;
use QueueTest\Mocks\Entity\DivergentQueueEntityFake;
use QueueTest\Mocks\Entity\DropBindExchangeEntityFake;
use QueueTest\Mocks\Entity\ExchangeEntityFake;
use QueueTest\Mocks\Entity\ExchangeRetryEntityFake;
use QueueTest\Mocks\Entity\QueueEntityFake;
use QueueTest\Mocks\Entity\TargetExchangeEntityFake;

/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

class ManagerTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Manager
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Manager();
    }

    public function tearDown()
    {
        $this->object = null;
    }

    public static function tearDownAfterClass()
    {
        // Only to Working With correctly Queues
        $manager = new Manager();
        $manager->setEntities(self::getEntities());
        $manager->update(ConnectionFake::amqp());
    }

    /**
     * @return AbstractEntity[]
     */
    private static function getEntities()
    {
        return array(
            new QueueEntityFake(),
            new ExchangeEntityFake(),
            new ExchangeRetryEntityFake(),
            new TargetExchangeEntityFake(),
            new BindQueueEntityFake(),
            new DropBindExchangeEntityFake(),
        );
    }

    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testEntitiesUpdate(Connection $connection)
    {
        $this->object->setEntities($this->getEntities());
        $this->object->addEntity(new BindExchangeEntityFake());
        $this->object->update($connection);
    }

    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testDivergentQueue(Connection $connection)
    {
        $this->object->addEntity(new QueueEntityFake());
        $this->object->addEntity(new DivergentQueueEntityFake());
        $this->object->update($connection);
    }
    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testDivergentExchange(Connection $connection)
    {
        $this->object->addEntity(new ExchangeEntityFake());
        $this->object->addEntity(new DivergentExchangeEntityFake());
        $this->object->update($connection);
    }

    public function connectionProvider()
    {
        return ConnectionFake::providerAll();
    }
}
 
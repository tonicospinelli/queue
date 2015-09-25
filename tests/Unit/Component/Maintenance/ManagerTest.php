<?php

use Queue\Component\EntityManager\Manager;
use Queue\Driver\Connection;
use Queue\Entity\AbstractEntity;
use QueueTest\Fake\ConnectionFake;
use QueueTest\Mocks\Entity\BindExchangeEntity;
use QueueTest\Mocks\Entity\BindQueueEntity;
use QueueTest\Mocks\Entity\DivergentExchangeEntity;
use QueueTest\Mocks\Entity\DivergentQueueEntity;
use QueueTest\Mocks\Entity\DropBindExchangeEntity;
use QueueTest\Mocks\Entity\ExchangeEntity;
use QueueTest\Mocks\Entity\ExchangeRetryEntity;
use QueueTest\Mocks\Entity\QueueEntity;
use QueueTest\Mocks\Entity\TargetExchangeEntity;

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
        $this->object = new Manager(true);
    }

    public function tearDown()
    {
        $this->object = null;
    }

    public static function tearDownAfterClass()
    {
        // Only to Working With correctly Queues
        $manager = new Manager(true);
        $manager->setEntities(self::getEntities());
        $manager->update(ConnectionFake::amqp());
    }

    /**
     * @return AbstractEntity[]
     */
    private static function getEntities()
    {
        return array(
            new QueueEntity(),
            new ExchangeEntity(),
            new ExchangeRetryEntity(),
            new TargetExchangeEntity(),
            new BindQueueEntity(),
            new DropBindExchangeEntity(),
        );
    }

    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testEntitiesUpdate(Connection $connection)
    {
        $this->object->setEntities($this->getEntities());
        $this->object->addEntity(new BindExchangeEntity());
        $this->object->update($connection);
    }

    /**
     * @expectedException \Queue\Driver\Exception\DivergentEntityException
     */
    public function testDivergentQueueWithException()
    {
        $manager = new Manager();
        $manager->addEntity(new QueueEntity());
        $manager->addEntity(new DivergentQueueEntity());
        $manager->update(ConnectionFake::amqp());
    }
    /**
     * @expectedException \Queue\Driver\Exception\DivergentEntityException
     */
    public function testDivergentExchangeWithException()
    {
        $manager = new Manager();
        $manager->addEntity(new ExchangeEntity());
        $manager->addEntity(new DivergentExchangeEntity());
        $manager->update(ConnectionFake::amqp());
    }

    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testDivergentQueue(Connection $connection)
    {
        $this->object->addEntity(new QueueEntity());
        $this->object->addEntity(new DivergentQueueEntity());
        $this->object->update($connection);
    }
    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testDivergentExchange(Connection $connection)
    {
        $this->object->addEntity(new ExchangeEntity());
        $this->object->addEntity(new DivergentExchangeEntity());
        $this->object->update($connection);
    }

    public function connectionProvider()
    {
        return ConnectionFake::providerAll();
    }
}
 
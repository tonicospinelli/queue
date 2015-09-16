<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Unit;


use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;
use QueueTest\Fake\Migration\QueueMigrationFake;

class QueueMigrationFakeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var QueueMigrationFake
     */
    protected $object;
    
    public function setUp()
    {
        $connection = DriverManager::getConnection(new Configuration(Driver::AMQP, RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD));
        $this->object = new QueueMigrationFake($connection);
    }
    
    public function tearDown()
    {
        $this->object = null;
    }
    
    public function testIfCanExecute()
    {
        $this->object->update();
    }

}
 
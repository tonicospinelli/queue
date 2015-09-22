<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.22
 *
 */

namespace QueueTest\Unit;


use Queue\Driver\Connection;
use QueueTest\Fake\ConnectionFake;

class ConnectionTest extends \PHPUnit_Framework_TestCase {


    /**
     * @dataProvider connectionProvider
     * @param Connection $connection
     */
    public function testClose(Connection $connection)
    {
        $connection->close();
    }

    public function connectionProvider()
    {
        return ConnectionFake::providerAll();
    }

}
 
<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.21
 *
 */

namespace QueueTest\Fake;

use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;

class ConnectionFake
{
    public static function inMemory($forceException = false)
    {
        return DriverManager::getConnection(new Configuration(Driver::IN_MEMORY, null, null, null, null, array('forceException' => $forceException)));
    }

    public static function amqp($forceException = false)
    {
        $host = ($forceException ? : RABBIT_HOST);
        return DriverManager::getConnection(new Configuration(Driver::AMQP, $host, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD));
    }

    public static function providerAll()
    {
        return array(
            array(self::amqp()),
            array(self::inMemory()),
        );
    }
}

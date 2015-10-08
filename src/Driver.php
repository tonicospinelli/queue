<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

namespace Queue;

use Queue\Driver\Connection;
use Queue\Driver\Exception\DriverException;

interface Driver
{
    const AMQP = 'amqp';
    const IN_MEMORY = 'inMemory';

    /**
     * Attempts to create a connection with the queue.
     *
     * @param ConfigurationInterface $configuration
     *
     * @return Connection The database connection.
     *
     * @throws DriverException
     */
    public function connect(ConfigurationInterface $configuration);
}

<?php

namespace Queue\Driver\InMemory;

use Queue\ConfigurationInterface;

class Driver implements \Queue\Driver
{
    /**
     * Attempts to create a connection with the queue.
     *
     * @param ConfigurationInterface $configuration
     * @return \Queue\Driver\Connection The queue connection.
     * @throws InMemoryException
     */
    public function connect(ConfigurationInterface $configuration)
    {
        try {
            return new Connection($configuration);
        } catch (\Exception $e) {
            throw new InMemoryException($e->getMessage(), null, $e);
        }
    }
}

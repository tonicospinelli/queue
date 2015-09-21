<?php

namespace Queue\Driver\SystemV;

use Queue\ConfigurationInterface;

class Driver implements \Queue\Driver
{
    /**
     * Attempts to create a connection with the queue.
     *
     * @param ConfigurationInterface $configuration
     * @return \Queue\Driver\Connection The queue connection.
     * @throws SystemVException
     */
    public function connect(ConfigurationInterface $configuration)
    {
        try {
            return new Connection($configuration);
        } catch (\Exception $e) {
            throw new SystemVException($e->getMessage(), null, $e);
        }
    }

    /**
     * Gets the name of driver.
     *
     * @return string The name of driver.
     */
    public function getName()
    {
        return self::SYSTEM_V;
    }
}

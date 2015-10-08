<?php

namespace Queue\Driver\InMemory;

use Queue\ConfigurationInterface;

class Driver implements \Queue\Driver
{
    /**
     * {@inheritdoc}
     */
    public function connect(ConfigurationInterface $configuration)
    {
        try {
            return new Connection($configuration);
        } catch (\Exception $e) {
            throw new InMemoryException($e->getMessage(), null, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::IN_MEMORY;
    }
}

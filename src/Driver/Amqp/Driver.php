<?php

namespace Queue\Driver\Amqp;

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
            throw new AmqpException($e->getMessage(), null, $e);
        }
    }
}

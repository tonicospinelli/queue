<?php

namespace Queue\Driver\Amqp;

use Queue\ConfigurationInterface;
use Queue\Resources\Amqp\Message;
use Queue\Resources\Amqp\Queue;
use Queue\Resources\Amqp\Exchange;

class Driver implements \Queue\Driver
{
    /**
     * @return string
     */
    public function getName()
    {
        return \Queue\Driver::AMQP;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(ConfigurationInterface $configuration)
    {
        try {
            return new Connection($configuration, $this);
        } catch (\Exception $e) {
            throw new AmqpException($e->getMessage(), null, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue($name, array $attributes = array())
    {
        return new Queue($name, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange($name, $type, array $attributes = array())
    {
        return new Exchange($name, $type, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage($message, array $properties = array(), $uid = null)
    {
        return new Message($message, $properties, $uid);
    }
}

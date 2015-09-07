<?php

namespace Queue\Driver\Redis;

use Queue\ConfigurationInterface;
use Queue\Resources\Redis\Exchange;
use Queue\Resources\Redis\Message;
use Queue\Resources\Redis\Queue;

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
            throw new RedisException($e->getMessage(), null, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::REDIS;
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

<?php

namespace Queue\Driver\Amqp;

use PhpAmqpLib\Message\AMQPMessage;
use Queue\Driver\MessageInterface as DriverMessageInterface;

interface MessageInterface extends DriverMessageInterface
{
    /**
     * @param AMQPMessage $message
     * @return MessageInterface
     */
    public static function create(AMQPMessage $message);

    /**
     * @param DriverMessageInterface $message
     * @return AMQPMessage
     */
    public static function createAMQPMessage(DriverMessageInterface $message);
}

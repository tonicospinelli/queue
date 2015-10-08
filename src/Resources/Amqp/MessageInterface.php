<?php

namespace Queue\Resources\Amqp;

use PhpAmqpLib\Message\AMQPMessage;
use Queue\Resources\MessageInterface as DriverMessageInterface;

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

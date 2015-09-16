<?php

namespace Queue\Driver\Amqp;

use PhpAmqpLib\Message\AMQPMessage;
use Queue\Driver\Message as DriverMessage;
use Queue\Driver\MessageInterface as DriverMessageInterface;

class Message extends DriverMessage implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public static function create(AMQPMessage $message)
    {
        $id = null;
        if ($message->has('delivery_tag')) {
            $id = $message->get('delivery_tag');
        }
        return new static($message->body, $message->get_properties(), $id);
    }

    /**
     * {@inheritdoc}
     */
    public static function createAMQPMessage(DriverMessageInterface $message)
    {
        return new AMQPMessage($message->getBody(), $message->getProperties());
    }
}

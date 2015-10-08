<?php

namespace Queue\Resources\Amqp;

use PhpAmqpLib\Message\AMQPMessage;
use Queue\Resources\Message as MessageResource;
use Queue\Resources\MessageInterface as DriverMessageInterface;

class Message extends MessageResource implements MessageInterface
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

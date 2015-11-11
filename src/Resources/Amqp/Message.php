<?php

namespace Queue\Resources\Amqp;

/**
 * Class Message is a Value Object
 */
class Message extends \Queue\Resources\Message implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setRequeue($confirm = true)
    {
        $this->setFlag(self::REQUEUE, $confirm);
    }

    /**
     * {@inheritdoc}
     */
    public function isRequeue()
    {
        return $this->isFlagSet(self::REQUEUE);
    }
}

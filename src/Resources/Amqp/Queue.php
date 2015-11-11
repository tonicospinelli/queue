<?php

namespace Queue\Resources\Amqp;

use Queue\Component\BitwiseFlag;
use Queue\Resources\Queue as QueueResource;

/**
 * Class Queue is a Value Object
 */
class Queue extends QueueResource implements QueueInterface
{
    use BitwiseFlag;

    /**
     * {@inheritdoc}
     */
    public function isDurable()
    {
        return $this->isFlagSet(self::DURABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDurable($confirm = true)
    {
        $this->setFlag(self::DURABLE, $confirm);
    }

    /**
     * {@inheritdoc}
     */
    public function isPassive()
    {
        return $this->isFlagSet(self::PASSIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPassive($confirm = true)
    {
        $this->setFlag(self::PASSIVE, $confirm);
    }

    /**
     * {@inheritdoc}
     */
    public function isAutoDelete()
    {
        return $this->isFlagSet(self::AUTO_DELETE);
    }

    /**
     * {@inheritdoc}
     */
    public function setAutoDelete($confirm = true)
    {
        $this->setFlag(self::AUTO_DELETE, $confirm);
    }

    /**
     * {@inheritdoc}
     */
    public function isExclusive()
    {
        return $this->isFlagSet(self::EXCLUSIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setExclusive($confirm = true)
    {
        $this->setFlag(self::EXCLUSIVE, $confirm);
    }
}

<?php

namespace Queue\Resources\Amqp;

use Queue\Component\BitwiseFlag;
use Queue\Resources\Queue as QueueResource;

class Queue extends QueueResource implements QueueInterface
{
    use BitwiseFlag;

    public function isDurable()
    {
        return $this->isFlagSet(self::DURABLE);
    }

    public function setDurable($confirm = true)
    {
        $this->setFlag(self::DURABLE, $confirm);
    }

    public function isPassive()
    {
        return $this->isFlagSet(self::PASSIVE);
    }

    public function setPassive($confirm = true)
    {
        $this->setFlag(self::PASSIVE, $confirm);
    }

    public function isAutoDelete()
    {
        return $this->isFlagSet(self::AUTO_DELETE);
    }

    public function setAutoDelete($confirm = true)
    {
        $this->setFlag(self::AUTO_DELETE, $confirm);
    }

    public function isExclusive()
    {
        return $this->isFlagSet(self::NO_WAIT);
    }

    public function setExclusive($confirm = true)
    {
        $this->setFlag(self::NO_WAIT, $confirm);
    }
}

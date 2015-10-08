<?php

namespace Queue\Resources\Amqp;

use Queue\Component\BitwiseFlag;
use Queue\Resources\Tunnel as TunnelResource;

class Tunnel extends TunnelResource implements TunnelInterface
{
    use BitwiseFlag;

    public function __construct($name, $type, array $attributes = array())
    {
        parent::__construct($name, $type, $attributes);
        $this->setPassive(false);
        $this->setDurable(false);
        $this->setAutoDelete(true);
    }

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

    public function isInternal()
    {
        return $this->isFlagSet(self::INTERNAL);
    }

    public function setInternal($confirm = true)
    {
        $this->setFlag(self::INTERNAL, $confirm);
    }
}

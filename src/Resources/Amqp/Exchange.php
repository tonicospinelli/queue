<?php

namespace Queue\Resources\Amqp;

use Queue\Component\BitwiseFlag;
use Queue\Resources\Exchange as BaseExchange;

/**
 * Class Exchange is a Value Object
 */
class Exchange extends BaseExchange implements ExchangeInterface
{
    use BitwiseFlag;

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $type, array $attributes = array())
    {
        parent::__construct($name, $type, $attributes);
        $this->setPassive(false);
        $this->setDurable(false);
        $this->setAutoDelete(true);
    }

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
    public function isInternal()
    {
        return $this->isFlagSet(self::INTERNAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setInternal($confirm = true)
    {
        $this->setFlag(self::INTERNAL, $confirm);
    }
}

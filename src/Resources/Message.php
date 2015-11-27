<?php

namespace Queue\Resources;

use Queue\Component\BitwiseFlag;

/**
 * Class Message is a Value Object.
 */
class Message extends Object implements MessageInterface
{
    use BitwiseFlag;
    private $uid;
    private $body;

    /**
     * Message constructor.
     * @param string $body
     * @param array $attributes
     * @param string $uid
     */
    public function __construct($body, array $attributes = array(), $uid = null)
    {
        parent::__construct($attributes);
        $this->body = $body;
        $this->uid = $uid;
    }

    /**
     * {@inheritdoc}
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setAck($confirm = true)
    {
        $this->setFlag(self::ACK, $confirm);
    }

    /**
     * {@inheritdoc}
     */
    public function isAck()
    {
        return $this->isFlagSet(self::ACK);
    }

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

<?php

namespace Queue\Resources;

use Queue\Component\BitwiseFlag;

class Message implements MessageInterface
{
    use BitwiseFlag;
    private $id;
    private $body;
    private $properties;

    public function __construct($body, array $properties = array(), $id = null)
    {
        $this->body = $body;
        $this->properties = $properties;
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
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
    public function getProperties()
    {
        return $this->properties;
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
    public function setRequeue($confirm = true)
    {
        $this->setFlag(self::REQUEUE, $confirm);
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
    public function isRequeue()
    {
        return $this->isFlagSet(self::REQUEUE);
    }
}

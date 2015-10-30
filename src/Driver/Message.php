<?php

namespace Queue\Driver;

use Queue\Component\BitwiseFlag;

class Message implements MessageInterface
{
    use BitwiseFlag;
    private $id;
    private $body;
    private $routingKey = '';
    protected $properties;

    public function __construct($body, array $properties = array(), $id = null)
    {
        $this->body = $body;
        $this->properties = $properties;
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param bool $confirm
     * @return void
     */
    public function setAck($confirm = true)
    {
        $this->setFlag(self::ACK, $confirm);
    }

    /**
     * @param bool $confirm
     * @return void
     */
    public function setNotAck($confirm = true)
    {
        $this->setFlag(self::NOT_ACK, $confirm);
    }

    /**
     * @param bool $confirm
     * @return void
     */
    public function setRequeue($confirm = true)
    {
        $this->setFlag(self::REQUEUE, $confirm);
    }

    /**
     * @return bool
     */
    public function isAck()
    {
        return $this->isFlagSet(self::ACK);
    }

    /**
     * @return bool
     */
    public function isNotAck()
    {
        return $this->isFlagSet(self::NOT_ACK);
    }

    /**
     * @return bool
     */
    public function isRequeue()
    {
        return $this->isFlagSet(self::REQUEUE);
    }

    /**
     * {@inheritdoc}
     */
    public function setRoutingKey($routingKey = '')
    {
        $this->routingKey = $routingKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }

    /**
     * @return void
     */
    public function renewTimeToLive()
    {}

}
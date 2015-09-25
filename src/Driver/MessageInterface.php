<?php

namespace Queue\Driver;

interface MessageInterface
{
    const ACK = 1;
    const NOT_ACK = 2;
    const REQUEUE = 4;

    public function __construct($body, array $properties = array(), $id = null);

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @return array
     */
    public function getProperties();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setAck($confirm = true);

    /**
     * @param bool $confirm
     * @return void
     */
    public function setNotAck($confirm = true);

    /**
     * @param bool $confirm
     * @return void
     */
    public function setRequeue($confirm = true);

    /**
     * @return bool
     */
    public function isAck();

    /**
     * @return bool
     */
    public function isNotAck();

    /**
     * @return bool
     */
    public function isRequeue();

    /**
     * @param string $routingKey
     * @return void
     */
    public function setRoutingKey($routingKey = '');

    /**
     * @return string
     */
    public function getRoutingKey();
}

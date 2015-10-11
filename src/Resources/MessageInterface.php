<?php

namespace Queue\Resources;

interface MessageInterface
{
    const ACK = 1;
    const REQUEUE = 2;

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
     * @param string $name
     * @param string $defaultValue
     * @return string
     */
    public function getProperty($name, $defaultValue = null);

    /**
     * @param bool $confirm
     * @return void
     */
    public function setAck($confirm = true);

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
    public function isRequeue();
}

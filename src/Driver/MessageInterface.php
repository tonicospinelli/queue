<?php

namespace Queue\Driver;

interface MessageInterface
{
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
}

<?php

namespace Queue\Driver;

class Message implements MessageInterface
{
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

    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }
}

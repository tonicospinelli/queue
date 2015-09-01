<?php

namespace Queue\Driver\InMemory;

use Queue\Driver\MessageInterface;

class Message implements MessageInterface
{
    protected $body;

    public function __construct($body, array $properties = array())
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}

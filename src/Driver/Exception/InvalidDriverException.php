<?php

namespace Queue\Driver\Exception;

class InvalidDriverException extends AbstractDriverException
{
    public function __construct($invalidDriverName)
    {
        $message = 'The given driver class ' . $invalidDriverName .
            ' must be a instance of \Queue\Driver';
        parent::__construct($message);
    }
}

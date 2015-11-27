<?php

namespace Queue\Driver\Exception;

class InvalidResourceException extends AbstractDriverException
{
    public function __construct($driver)
    {
        parent::__construct(
            'The ' . $driver . ' is only compatible with '.
            'resources from \Queue\Resources\\' . ucfirst($driver)
        );
    }
}

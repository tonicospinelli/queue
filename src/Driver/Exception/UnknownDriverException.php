<?php

namespace Queue\Driver\Exception;

class UnknownDriverException extends AbstractDriverException
{
    public function __construct($unknownDriverName, array $knownDrivers = array())
    {
        $message = "The given 'driver' " . $unknownDriverName . " is unknown, " .
            "Queue Lib currently supports only the following drivers: " .
            implode(", ", $knownDrivers);
        parent::__construct($message);
    }
}

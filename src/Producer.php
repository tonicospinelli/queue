<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Resources\MessageInterface;
use Queue\Resources\Tunnel;

abstract class Producer extends AbstractProcess implements ProducerInterface
{
    /**
     * @var Tunnel
     */
    private $tunnel;

    public function __construct(DriverConnection $connection, Tunnel $tunnel)
    {
        parent::__construct($connection);
        $this->tunnel = $tunnel;
    }

    /**
     * @return Tunnel
     */
    public function getTunnel()
    {
        return $this->tunnel;
    }

    /**
     * @param string $message
     * @return MessageInterface
     */
    public function prepare($message)
    {
        return $this->getConnection()->prepare($message);
    }

    /**
     * @param MessageInterface $message
     * @return void
     */
    final public function publish(MessageInterface $message, $patternKey = '')
    {
        $this->getConnection()->publish($message, $this->getTunnel());
    }
}

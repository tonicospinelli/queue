<?php

namespace Queue\Resources;

class Binding
{
    /**
     * @var Queue
     */
    private $queue;
    /**
     * @var Tunnel
     */
    private $tunnel;
    /**
     * @var array
     */
    private $patternKeys;

    public function __construct(Tunnel $tunnel, Queue $queue, array $patternKeys = array())
    {
        $this->tunnel = $tunnel;
        $this->queue = $queue;
        $this->patternKeys = $patternKeys;
    }

    public static function createFromConfiguration(Tunnel $tunnel, Queue $queue, array $patternKeys = array())
    {
        return new self($tunnel, $queue, $patternKeys);
    }

    /**
     * @return Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return Tunnel
     */
    public function getTunnel()
    {
        return $this->tunnel;
    }

    /**
     * @return array
     */
    public function getPatternKeys()
    {
        return $this->patternKeys;
    }
}

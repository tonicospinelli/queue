<?php

namespace Queue\Resources;

class Route
{
    /**
     * @var Queue[]
     */
    private $queues;
    /**
     * @var Tunnel
     */
    private $tunnel;
    /**
     * @var string
     */
    private $name;

    /**
     * @param Tunnel $tunnel
     * @param Queue[] $queues
     * @param string $name
     */
    public function __construct(Tunnel $tunnel, array $queues, $name = '')
    {
        $this->tunnel = $tunnel;
        $this->name = $name;
        $this->setQueues($queues);
    }

    /**
     * @return Queue[]
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * @param Queue $queue
     * @return void
     */
    public function addQueue(Queue $queue)
    {
        if (!in_array($queue, $this->queues)) {
            $this->queues[] = $queue;
        }
    }

    /**
     * @param Queue[] $queues
     * @return void
     */
    public function setQueues(array $queues)
    {
        foreach ($queues as $queue) {
            $this->addQueue($queue);
        }
    }

    /**
     * @return Tunnel
     */
    public function getTunnel()
    {
        return $this->tunnel;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

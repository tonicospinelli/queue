<?php

namespace Queue\Resources;

interface ExchangeInterface extends AttributeInterface
{
    const TYPE_DIRECT = 'direct';
    const TYPE_FANOUT = 'fanout';
    const TYPE_TOPIC = 'topic';
    const TYPE_HEADERS = 'headers';

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return QueueInterface[]
     */
    public function getBindings();

    /**
     * @param string $name
     * @return QueueInterface[]
     */
    public function getQueuesFromBinding($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasBinding($name);

    /**
     * @param array $bindings
     */
    public function setBindings(array $bindings);

    /**
     * @param QueueInterface $queue
     * @param string $routingKey
     * @return void
     */
    public function addBinding(QueueInterface $queue, $routingKey = '');
}

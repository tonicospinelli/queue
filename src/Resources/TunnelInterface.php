<?php

namespace Queue\Resources;

interface TunnelInterface extends AttributeInterface
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
     * @return array
     */
    public function getRoutes();

    /**
     * @param string $name
     * @return QueueInterface[]
     */
    public function getQueuesFromRoute($name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasRoute($name);

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes);

    /**
     * @param $queueName
     * @param string $routeName
     * @return void
     */
    public function addRoute($queueName, $routeName = '');
}

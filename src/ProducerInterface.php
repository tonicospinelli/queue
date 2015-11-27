<?php

namespace Queue;

use Queue\Resources\MessageInterface;
use Queue\Resources\ExchangeInterface;

interface ProducerInterface
{
    /**
     * @return ExchangeInterface
     */
    public function getExchange();

    /**
     * @param $message
     * @return MessageInterface
     */
    public function prepare($message);

    /**
     * @param MessageInterface $message
     * @param string $routingKey
     * @return void
     */
    public function publish(MessageInterface $message, $routingKey = '');
}

<?php

namespace Queue\Driver;

use Queue\Driver as BaseDriver;
use Queue\Driver\Exception\DivergentStructureException;
use Queue\Resources\MessageInterface;
use Queue\Resources\QueueInterface;
use Queue\Resources\ExchangeInterface;

interface Connection
{
    /**
     * @return BaseDriver
     */
    public function getDriver();

    /**
     * @return string
     */
    public function getDriverName();

    /**
     * Close connection to the server.
     * @return void
     */
    public function close();

    /**
     * Publish a message.
     * @param MessageInterface $message The message.
     * @param ExchangeInterface $exchange The exchange resource.
     * @param string $routingKey [optional] The pattern key.
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return void
     */
    public function publish(MessageInterface $message, ExchangeInterface $exchange, $routingKey = '');

    /**
     * @param QueueInterface $queue
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return MessageInterface|null
     */
    public function fetchOne(QueueInterface $queue);

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function ack(MessageInterface $message);

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function nack(MessageInterface $message);

    /**
     * Creates a queue to storage messages.
     *
     * @param QueueInterface $queue The queue resource.
     * @throws BaseDriver\Exception\InvalidResourceException
     * @throws DivergentStructureException
     * @return void
     */
    public function createQueue(QueueInterface $queue);

    /**
     * Delete a queue from given name.
     * @param QueueInterface $queue The queue resource.
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return void
     */
    public function deleteQueue(QueueInterface $queue);

    /**
     * A client writes messages to a exchange.
     * The exchange forwards each message on to zero or more queues based on the message’s pattern key.
     *
     * @param ExchangeInterface $exchange the exchange resource
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return void
     */
    public function createExchange(ExchangeInterface $exchange);

    /**
     * @param ExchangeInterface $exchange
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return void
     */
    public function deleteExchange(ExchangeInterface $exchange);

    /**
     * A binding is a relationship between a exchange and a queue.
     * This can be simply read as: the queue is interested in messages from this exchange.
     *
     * @param QueueInterface $queue The queue to bind.
     * @param ExchangeInterface $exchange The exchange to bind.
     * @param string $routingKey The route key name to bind.
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return void
     */
    public function bind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '');

    /**
     * Unbinding a exchange and a queue.
     *
     * @param QueueInterface $queue The Queue.
     * @param ExchangeInterface $exchange The exchange.
     * @param string $routingKey [optional] Depending on the exchange type, the exchange may or
     *                           may not use the Route Key to determine the queues to which it
     *                           should publish the message.
     * @throws BaseDriver\Exception\InvalidResourceException
     * @return void
     */
    public function unbind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '');
}

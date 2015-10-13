<?php

namespace Queue\Driver;

use Queue\Driver\Exception\DivergentStructureException;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

interface Connection
{
    /**
     * Close connection to the server.
     * @return void
     */
    public function close();

    /**
     * Publish a message.
     * @param MessageInterface $message The message.
     * @param Tunnel $tunnel The tunnel resource.
     * @param string $patternKey [optional] The pattern key.
     * @return void
     */
    public function publish(MessageInterface $message, Tunnel $tunnel, $patternKey = '');

    /**
     * @param string $message
     * @param array $properties
     * @param string $id
     * @return MessageInterface
     */
    public function prepare($message, array $properties = array(), $id = null);

    /**
     * @param string $queueName
     * @return MessageInterface|null
     */
    public function fetchOne($queueName);

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
     * @param Queue $queue The queue resource.
     * @throws DivergentStructureException
     * @return void
     */
    public function createQueue(Queue $queue);

    /**
     * Delete a queue from given name.
     * @param Queue $queue The queue resource.
     * @return void
     */
    public function deleteQueue(Queue $queue);

    /**
     * A client writes messages to a tunnel.
     * The tunnel forwards each message on to zero or more queues based on the message’s pattern key.
     *
     * @param Tunnel $tunnel the tunnel resource
     * @return void
     */
    public function createTunnel(Tunnel $tunnel);

    /**
     * @param Tunnel $tunnel
     * @return void
     */
    public function deleteTunnel(Tunnel $tunnel);

    /**
     * A binding is a relationship between a tunnel and a queue.
     * This can be simply read as: the queue is interested in messages from this tunnel.
     *
     * @param string $queue The queue name to bind.
     * @param string $tunnel The tunnel name to bind.
     * @param string $routeKey The route key name to bind.
     * @return void
     */
    public function bind($queue, $tunnel, $routeKey = '');

    /**
     * Unbinding a tunnel and a queue.
     *
     * @param string $queue The Queue name.
     * @param string $tunnel The tunnel name.
     * @param string $routeKey [optional] Depending on the tunnel type, the tunnel may or may not use the Route Key
     *                           to determine the queues to which it should publish the message.
     * @return void
     */
    public function unbind($queue, $tunnel, $routeKey = '');
}

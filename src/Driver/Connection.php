<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

namespace Queue\Driver;

use Queue\ConsumerInterface;
use Queue\Exchange;
use Queue\ProducerInterface;

interface Connection
{

    /**
     * @return void
     */
    public function close();

    /**
     * @param MessageInterface $message
     * @param ProducerInterface $producer
     * @return void
     */
    public function publish(MessageInterface $message, ProducerInterface $producer);

    /**
     * @param string $message
     * @param array $properties
     * @param string $id
     * @return MessageInterface
     */
    public function prepare($message, array $properties = array(), $id = null);

    /**
     * @param ConsumerInterface $consumer
     * @return MessageInterface|null
     */
    public function fetchOne(ConsumerInterface $consumer);

    /**
     * @return Exchange
     */
    public function getExchange();

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
}

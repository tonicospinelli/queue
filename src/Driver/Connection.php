<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.08.28
 *
 */

namespace Queue\Driver;

use Queue\ConsumerInterface;
use Queue\Driver\Exception\DivergentEntityException;
use Queue\Exchange;
use Queue\Entity\AbstractExchange;
use Queue\Entity\AbstractQueue;
use Queue\Entity\AbstractBind;

interface Connection
{

    /**
     * @return void
     */
    public function close();

    /**
     * @param MessageInterface $message
     * @param AbstractExchange $producer
     * @return void
     */
    public function publish(MessageInterface $message, AbstractExchange $exchange);

    /**
     * @param string $message
     * @param array $properties
     * @param string $id
     * @return MessageInterface
     */
    public function prepare($message, array $properties = array(), $id = null);

    /**
     * @param AbstractQueue $queue
     * @return MessageInterface|null
     */
    public function fetchOne(AbstractQueue $queue);

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
     * @param AbstractQueue $queue
     * @throws DivergentEntityException
     * @return void
     */
    public function createQueue(AbstractQueue $queue);

    /**
     * @param AbstractQueue $queue
     * @return void
     */
    public function dropQueue(AbstractQueue $queue);


    /**
     * @param AbstractExchange $exchange
     * @throws DivergentEntityException
     * @return void
     */
    public function createExchange(AbstractExchange $exchange);

    /**
     * @param AbstractExchange $exchange
     * @return void
     */
    public function dropExchange(AbstractExchange $exchange);


    /**
     * @param AbstractBind $bind
     * @return void
     */
    public function createBind(AbstractBind $bind);

    /**
     * @param AbstractBind $bind
     * @return void
     */
    public function dropBind(AbstractBind $bind);


}

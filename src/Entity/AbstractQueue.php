<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;

use Queue\Driver\Connection as DriverConnection;
use Queue\Driver\Exception\DivergentEntityException;

abstract class AbstractQueue extends AbstractEntity implements InterfaceQueue
{

    /**
     * @var bool
     */
    protected $durable = self::QUEUE_DURABLE;

    /**
     * @var bool
     */
    protected $autoDelete = self::QUEUE_NOT_AUTO_DELETE;

    /**
     * @var array
     */
    protected $queueArguments = array();

    /**
     * @return boolean
     */
    public function isAutoDelete()
    {
        return $this->autoDelete;
    }

    /**
     * @return boolean
     */
    public function isDurable()
    {
        return $this->durable;
    }

    /**
     * @return array
     */
    public function getQueueArguments()
    {
        return $this->queueArguments;
    }

    abstract function getQueueName();

    final protected function execute(DriverConnection $connection)
    {
        try {
            $connection->createQueue($this);
        } catch (DivergentEntityException $e) {
            $this->askConfirmation();
            $connection->dropQueue($this);
            $connection->createQueue($this);
        }
    }

    private function askConfirmation()
    {
        echo 'confirm delete current queue: '.$this->getQueueName() . PHP_EOL;
    }

}
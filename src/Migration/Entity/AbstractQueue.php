<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Migration\Entity;


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

    final protected function execute()
    {
        try {
            $this->createQueue();
        } catch (\Exception $e) {
            $this->askConfirmation();
            $this->dropQueue();
            $this->createQueue();
        }
    }

    private function createQueue()
    {
        $this->connection->createQueue($this);
    }

    private function askConfirmation()
    {
        echo 'confirm delete current queue: '.$this->getQueueName() . PHP_EOL;
    }

    private function dropQueue()
    {
        $this->connection->dropQueue($this);
    }


}
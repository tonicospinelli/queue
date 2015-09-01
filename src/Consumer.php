<?php

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Driver\MessageInterface;

abstract class Consumer extends AbstractQueue implements ConsumerInterface
{
    /**
     * @var bool
     */
    private $persistent;

    public function __construct(DriverConnection $connection, $persistent = self::NOT_PERSISTENT)
    {
        parent::__construct($connection);
        $this->persistent = $persistent;
    }

    /**
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * @return void
     */
    final public function consume()
    {
        while (($message = $this->getConnection()->fetchOne($this)) !== null || $this->isPersistent()) {
	        if ($message instanceof MessageInterface) {
                $this->process($message);
            }
        }
    }
}

<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace Queue\Exception;

use Exception;
use Queue\Entity\AbstractExchange;

class RetryQueueException extends QueueException
{
    /**
     * @var AbstractExchange
     */
    protected $exchange;

    /**
     * @param AbstractExchange $exchangeEntity
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(AbstractExchange $exchangeEntity, $message = "", $code = 0, Exception $previous = null)
    {
        $this->exchange = $exchangeEntity;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return AbstractExchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }
}
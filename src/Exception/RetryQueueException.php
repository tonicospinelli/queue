<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace Queue\Exception;

use Exception;
use Queue\Resources\ExchangeInterface;

class RetryQueueException extends QueueException
{
    /**
     * @var ExchangeInterface
     */
    protected $exchange;

    /**
     * @param ExchangeInterface $exchange
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct(ExchangeInterface $exchange, $message = "", $code = 0, Exception $previous = null)
    {
        $this->exchange = $exchange;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ExchangeInterface
     */
    public function getExchange()
    {
        return $this->exchange;
    }
}

<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace Queue;

use Queue\Driver\Connection as DriverConnection;
use Queue\Resources\ExchangeInterface;

final class ProducerRetry extends Producer
{
    /**
     * @var ExchangeInterface
     */
    protected $exchange;

    /**
     * @param DriverConnection $connection
     * @param ExchangeInterface $exchange
     */
    public function __construct(DriverConnection $connection, ExchangeInterface $exchange)
    {
        $this->exchange = $exchange;
        parent::__construct($connection, $exchange);
    }
}

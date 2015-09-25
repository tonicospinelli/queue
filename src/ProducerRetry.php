<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace Queue;


use Queue\Driver\Connection as DriverConnection;
use Queue\Entity\AbstractExchange;
use Queue\Entity\InterfaceExchange;
use QueueTest\Mocks\Entity\ExchangeRetryEntity;

final class ProducerRetry extends Producer
{
    protected $exchange;

    public function exchange()
    {
        return $this->exchange;
    }

    public function __construct(DriverConnection $connection, AbstractExchange $exchange)
    {
        $this->exchange = $exchange;
        parent::__construct($connection);
    }


}
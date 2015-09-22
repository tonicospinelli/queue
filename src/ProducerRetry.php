<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace Queue;


use Queue\Entity\InterfaceExchange;
use QueueTest\Mocks\Entity\ExchangeRetryEntityFake;

final class ProducerRetry extends Producer
{
    const RETRY_SUFFIX = '.retry';
    /**
     * @var Consumer
     */
    protected  $consumer;

    /**
     * @return InterfaceExchange
     */
    public function exchange()
    {
        return new ExchangeRetryEntityFake();
    }

    /**
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
        parent::__construct($consumer->getConnection());
    }

}
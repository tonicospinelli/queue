<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.11
 *
 */

namespace Queue;


use Queue\Driver\Connection as DriverConnection;

final class ProducerRetry extends Producer
{
    const RETRY_SUFFIX = '.retry';
    /**
     * @var Consumer
     */
    protected  $consumer;

    /**
     * @param Consumer $consumer
     */
    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
        parent::__construct($consumer->getConnection());
    }

    public function getWorkingQueueName()
    {
        // TODO: Improvement this when 'Routing keys' became ready
        return $this->consumer->getWorkingQueueName().self::RETRY_SUFFIX;
    }

    public function getWorkingExchangeName()
    {
        // TODO: Improvement this when 'Routing keys' became ready
        return $this->consumer->getWorkingExchangeName().self::RETRY_SUFFIX;
    }

    public function getQueueArguments()
    {
        return array(
            'x-message-ttl' => array('I', $this->consumer->getTimeToLiveInMilliseconds()),
            'x-dead-letter-exchange' => array('S', $this->consumer->getWorkingExchangeName())
        );
    }


}
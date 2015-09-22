<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


class DivergentQueueEntityFake extends QueueEntityFake
{
    protected $durable = self::QUEUE_NOT_DURABLE;
}
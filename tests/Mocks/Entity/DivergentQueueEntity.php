<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


class DivergentQueueEntity extends QueueEntity
{
    protected $durable = self::QUEUE_NOT_DURABLE;
}
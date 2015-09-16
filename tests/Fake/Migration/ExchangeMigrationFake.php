<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Fake\Migration;


use Queue\Migration\Entity\AbstractExchange;

class ExchangeMigrationFake extends AbstractExchange
{
    public function getExchangeName()
    {
        return 'migration.queue.test';
    }

    public function getType()
    {
        return self::TYPE_FANOUT;
    }
}
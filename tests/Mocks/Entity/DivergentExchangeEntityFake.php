<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


class DivergentExchangeEntityFake extends ExchangeEntityFake
{
    public function getType()
    {
        return self::TYPE_DIRECT;
    }
}
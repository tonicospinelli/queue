<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.15
 *
 */

namespace QueueTest\Mocks\Entity;


class DivergentExchangeEntity extends ExchangeEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE_DIRECT;
    }
}
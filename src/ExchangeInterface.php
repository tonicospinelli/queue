<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.01
 *
 */

namespace Queue;

interface ExchangeInterface
{
    const AMQP_CHANNEL_DIRECT = 'direct';
    const AMQP_CHANNEL_FANOUT = 'fanout';
    const AMQP_CHANNEL_TOPIC = 'topic';
    const AMQP_CHANNEL_MATCH = 'match';
    const AMQP_PASSIVE_TRUE = true;
    const AMQP_PASSIVE_FALSE = false;
    const AMQP_DURABLE_TRUE = true;
    const AMQP_DURABLE_FALSE = false;
    const AMQP_AUTO_DELETE_TRUE = true;
    const AMQP_AUTO_DELETE_FALSE = false;
    const AMQP_INTERNAL_TRUE = true;
    const AMQP_INTERNAL_FALSE = false;
    const AMQP_NO_WAIT = true;
    const AMQP_WAIT = false;

    public function getChannel();
} 
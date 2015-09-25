<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;


interface InterfaceExchange
{
    const TYPE_DIRECT = 'direct';
    const TYPE_FANOUT = 'fanout';
    const TYPE_TOPIC = 'topic';
    const TYPE_MATCH = 'match';

    const EXCHANGE_DURABLE = true;
    const EXCHANGE_NOT_DURABLE = false;
    const EXCHANGE_AUTO_DELETE = true;
    const EXCHANGE_NOT_AUTO_DELETE = false;
    const EXCHANGE_INTERNAL = true;
    const EXCHANGE_NOT_INTERNAL = false;
}
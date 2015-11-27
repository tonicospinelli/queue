<?php

namespace Queue\Resources\Amqp;

use Queue\Resources\AutoDelete;
use Queue\Resources\Durable;
use Queue\Resources\Internal;
use Queue\Resources\Passive;
use Queue\Resources\ExchangeInterface as BaseExchangeInterface;

interface ExchangeInterface extends ResourceInterface, BaseExchangeInterface, Durable, Passive, AutoDelete, Internal
{
    const DURABLE = 1;
    const PASSIVE = 2;
    const AUTO_DELETE = 4;
    const INTERNAL = 8;
}

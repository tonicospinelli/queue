<?php

namespace Queue\Resources\Amqp;

use Queue\Resources\AutoDelete;
use Queue\Resources\Durable;
use Queue\Resources\Internal;
use Queue\Resources\Passive;

interface TunnelInterface extends Durable, Passive, AutoDelete, Internal
{
    const DURABLE = 1;
    const PASSIVE = 2;
    const AUTO_DELETE = 4;
    const INTERNAL = 8;
}

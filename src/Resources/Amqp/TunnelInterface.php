<?php

namespace Queue\Resources\Amqp;

use Queue\Resources\AutoDelete;
use Queue\Resources\Durable;
use Queue\Resources\Internal;
use Queue\Resources\Passive;
use Queue\Resources\TunnelInterface as ResourceTunnelInterface;

interface TunnelInterface extends ResourceInterface, ResourceTunnelInterface, Durable, Passive, AutoDelete, Internal
{
    const DURABLE = 1;
    const PASSIVE = 2;
    const AUTO_DELETE = 4;
    const INTERNAL = 8;
}

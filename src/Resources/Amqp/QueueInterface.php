<?php

namespace Queue\Resources\Amqp;

use Queue\Resources\AutoDelete;
use Queue\Resources\Durable;
use Queue\Resources\Exclusive;
use Queue\Resources\Passive;

interface QueueInterface extends Durable, Passive, AutoDelete, Exclusive
{
    const DURABLE = 1;
    const PASSIVE = 2;
    const AUTO_DELETE = 4;
    const NO_WAIT = 8;
}

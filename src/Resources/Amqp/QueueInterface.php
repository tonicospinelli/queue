<?php

namespace Queue\Resources\Amqp;

use \Queue\Resources\QueueInterface as ResourceQueueInterface;
use Queue\Resources\AutoDelete;
use Queue\Resources\Durable;
use Queue\Resources\Exclusive;
use Queue\Resources\Passive;

interface QueueInterface extends ResourceInterface, ResourceQueueInterface, Durable, Passive, AutoDelete, Exclusive
{
    const DURABLE = 1;
    const PASSIVE = 2;
    const AUTO_DELETE = 4;
    const EXCLUSIVE = 8;
}

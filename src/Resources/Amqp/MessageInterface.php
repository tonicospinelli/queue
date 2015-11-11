<?php

namespace Queue\Resources\Amqp;

interface MessageInterface extends ResourceInterface, \Queue\Resources\MessageInterface
{
    const REQUEUE = 2;

    /**
     * @param bool $confirm
     * @return void
     */
    public function setRequeue($confirm = true);

    /**
     * @return bool
     */
    public function isRequeue();
}

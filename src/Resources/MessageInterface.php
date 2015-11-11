<?php

namespace Queue\Resources;

interface MessageInterface extends AttributeInterface
{
    const ACK = 1;

    /**
     * @return string
     */
    public function getUid();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setAck($confirm = true);

    /**
     * @return bool
     */
    public function isAck();
}

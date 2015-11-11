<?php

namespace Queue\Resources;

interface Durable
{

    /**
     * @return bool
     */
    public function isDurable();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setDurable($confirm = true);
}

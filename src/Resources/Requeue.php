<?php

namespace Queue\Resources;

interface Requeue
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

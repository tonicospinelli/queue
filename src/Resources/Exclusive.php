<?php

namespace Queue\Resources;

interface Exclusive
{

    /**
     * @return bool
     */
    public function isExclusive();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setExclusive($confirm = true);
}

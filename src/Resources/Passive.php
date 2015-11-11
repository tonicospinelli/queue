<?php

namespace Queue\Resources;

interface Passive
{

    /**
     * @return bool
     */
    public function isPassive();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setPassive($confirm = true);
}

<?php

namespace Queue\Resources;

interface Passive
{
    public function isPassive();

    public function setPassive($confirm = true);
}

<?php

namespace Queue\Resources;

interface Exclusive
{
    public function isExclusive();

    public function setExclusive($confirm = true);
}

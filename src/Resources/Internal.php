<?php

namespace Queue\Resources;

interface Internal
{
    public function isInternal();

    public function setInternal($confirm = true);
}

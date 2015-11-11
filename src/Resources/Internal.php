<?php

namespace Queue\Resources;

interface Internal
{
    /**
     * @return bool
     */
    public function isInternal();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setInternal($confirm = true);
}

<?php

namespace Queue\Resources;

interface AutoDelete
{
    /**
     * @return bool
     */
    public function isAutoDelete();

    /**
     * @param bool $confirm
     * @return void
     */
    public function setAutoDelete($confirm = true);
}

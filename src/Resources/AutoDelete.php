<?php

namespace Queue\Resources;

interface AutoDelete
{
    public function isAutoDelete();

    public function setAutoDelete($confirm = true);
}

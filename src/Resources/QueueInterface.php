<?php

namespace Queue\Resources;

interface QueueInterface extends AttributeInterface
{
    /**
     * Gets queue name.
     * @return string
     */
    public function getName();
}

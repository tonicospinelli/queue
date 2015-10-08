<?php

namespace Queue;

use Queue\Resources\MessageInterface;

interface ProducerInterface
{
    /**
     * @param MessageInterface $message
     * @param string $patternKey
     * @return void
     */
    public function publish(MessageInterface $message, $patternKey = '');
}

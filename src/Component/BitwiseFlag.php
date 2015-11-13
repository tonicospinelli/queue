<?php

namespace Queue\Component;

trait BitwiseFlag
{
    /**
     * @var int
     */
    protected $flags;

    /**
     * @param int $flag
     * @return bool
     */
    protected function isFlagSet($flag)
    {
        return (($this->flags & $flag) == $flag);
    }

    /**
     * @param int $flag
     * @param bool $value
     * @return void
     */
    protected function setFlag($flag, $value)
    {
        if ($value) {
            $this->flags |= $flag;
            return;
        }
        $this->flags &= ~$flag;
    }
}

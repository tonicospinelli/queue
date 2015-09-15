<?php
/**
 * Created by PhpStorm.
 * User: aspinelli
 * Date: 9/15/15
 * Time: 10:08 AM
 * @author Antonio Spinelli <antonio.spinelli@kanui.com.br>
 */

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
        } else {
            $this->flags &= ~$flag;
        }
    }
}

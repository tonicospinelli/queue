<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;


abstract class AbstractBindQueue extends AbstractBind implements InterfaceBind
{
    /**
     * @return AbstractQueue
     */
    abstract function getTargetQueue();
}
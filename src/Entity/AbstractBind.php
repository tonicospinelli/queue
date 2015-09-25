<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;

use Queue\Driver\Connection as DriverConnection;

abstract class AbstractBind extends AbstractEntity implements InterfaceBind
{
    protected $invalid = false;

    /**
     * @return string
     */
    abstract function getRoutingKey();

    /**
     * @return AbstractExchange
     */
    abstract function getExchange();


    public function isDeprecated()
    {
        return $this->invalid;
    }
} 
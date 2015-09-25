<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Entity;

use Queue\Driver\Connection as DriverConnection;
use Queue\Driver\Exception\DivergentEntityException;


abstract class AbstractExchange extends AbstractEntity implements InterfaceExchange
{

    /**
     * @var bool
     */
    protected $durable = self::EXCHANGE_DURABLE;

    /**
     * @var bool
     */
    protected $autoDelete = self::EXCHANGE_NOT_AUTO_DELETE;

    /**
     * @var bool
     */
    protected $internal = self::EXCHANGE_NOT_INTERNAL;

    /**
     * @var array
     */
    protected $exchangeArguments = array();

    /**
     * @return boolean
     */
    public function isAutoDelete()
    {
        return $this->autoDelete;
    }

    /**
     * @return boolean
     */
    public function isDurable()
    {
        return $this->durable;
    }

    /**
     * @return array
     */
    public function getExchangeArguments()
    {
        return $this->exchangeArguments;
    }

    /**
     * @return boolean
     */
    public function isInternal()
    {
        return $this->internal;
    }

    abstract function getExchangeName();

    abstract function getType();

} 
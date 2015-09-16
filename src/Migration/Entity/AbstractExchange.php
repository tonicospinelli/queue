<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.14
 *
 */

namespace Queue\Migration\Entity;

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

    final protected function execute()
    {
        try {
            $this->createExchange();
        } catch (\Exception $e) {
            $this->askConfirmation();
            $this->dropExchange();
            $this->createExchange();
        }
    }

    private function createExchange()
    {
        $this->connection->createExchange($this);
    }

    private function askConfirmation()
    {
        echo 'confirm delete current exchange: '.$this->getExchangeName() . PHP_EOL;
    }

    private function dropExchange()
    {
        $this->connection->dropExchange($this);
    }
} 
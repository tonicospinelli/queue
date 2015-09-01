<?php
/**
 * @author Marco.Souza<marco.souza@tricae.com.br>
 * @since 2015.09.01
 *
 */
namespace Queue;

abstract class Exchange implements ExchangeInterface
{

    /**
     * @var string
     */
    protected $channel = self::AMQP_CHANNEL_DIRECT;
    /**
     * @var bool
     */
    protected $passive = self::AMQP_PASSIVE_FALSE;

    /**
     * @var bool
     */
    protected $durable = self::AMQP_DURABLE_FALSE;

    /**
     * @var bool
     */
    protected $autoDelete = self::AMQP_AUTO_DELETE_FALSE;

    /**
     * @var bool
     */
    protected $internal = self::AMQP_INTERNAL_FALSE;

    /**
     * @var bool
     */
    protected $noWait = self::AMQP_WAIT;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @var int|null
     */
    protected $tickets = null;

    /**
     * @param array $params
     */
    public function __construct(array $params = array())
    {
        $this->bindParams($params);

    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel = self::AMQP_CHANNEL_DIRECT)
    {
        $this->channel = $channel;
    }

    /**
     * @return boolean
     */
    public function isAutoDelete()
    {
        return $this->autoDelete;
    }

    /**
     * @param boolean $autoDelete
     */
    public function setAutoDelete($autoDelete = self::AMQP_AUTO_DELETE_FALSE)
    {
        $this->autoDelete = $autoDelete;
    }

    /**
     * @return boolean
     */
    public function isDurable()
    {
        return $this->durable;
    }

    /**
     * @param boolean $durable
     */
    public function setDurable($durable = self::AMQP_DURABLE_FALSE)
    {
        $this->durable = $durable;
    }

    /**
     * @return boolean
     */
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * @param boolean $internal
     */
    public function setInternal($internal = self::AMQP_INTERNAL_FALSE)
    {
        $this->internal = $internal;
    }

    /**
     * @return boolean
     */
    public function isNoWait()
    {
        return $this->noWait;
    }

    /**
     * @param boolean $noWait
     */
    public function setNoWait($noWait = self::AMQP_WAIT)
    {
        $this->noWait = $noWait;
    }

    /**
     * @return boolean
     */
    public function isPassive()
    {
        return $this->passive;
    }

    /**
     * @param boolean $passive
     */
    public function setPassive($passive = self::AMQP_PASSIVE_FALSE)
    {
        $this->passive = $passive;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return int
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param int $tickets
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;
    }

    private function bindParams(array $params)
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key))
            {
                $this->set{$key}($value);
            }
        }

    }

} 
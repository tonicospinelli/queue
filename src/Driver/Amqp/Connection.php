<?php

namespace Queue\Driver\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use PhpAmqpLib\Message\AMQPMessage;
use Queue\Component\BitwiseFlag;
use Queue\ConfigurationInterface;
use Queue\Driver as BaseDriver;
use Queue\Driver\Exception\InvalidResourceException;
use Queue\Resources\QueueInterface;
use Queue\Resources\MessageInterface;
use Queue\Resources\ExchangeInterface;

class Connection implements \Queue\Driver\Connection
{
    use BitwiseFlag;
    const NO_WAIT = 1;

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var BaseDriver
     */
    private $driver;

    public function __construct(ConfigurationInterface $configuration, BaseDriver $driver)
    {
        $this->driver = $driver;
        $this->connection = new AMQPStreamConnection(
            $configuration->getHostname(),
            $configuration->getPort(),
            $configuration->getUsername(),
            $configuration->getPassword(),
            $configuration->getOption('vhost', '/'),
            $configuration->getOption('insist', false),
            $configuration->getOption('login_method', 'AMQPLAIN'),
            $configuration->getOption('login_response', null),
            $configuration->getOption('locale', 'en_US'),
            $configuration->getOption('connection_timeout', 3),
            $configuration->getOption('read_write_timeout', 3),
            $configuration->getOption('context', null),
            $configuration->getOption('keepalive', false),
            $configuration->getOption('heartbeat', 0)
        );
        $this->setNoWait($configuration->getOption('no_wait', false));
    }

    /**
     * @return BaseDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function getDriverName()
    {
        return $this->getDriver()->getName();
    }

    /**
     * @param boolean $confirm
     */
    public function setNoWait($confirm = true)
    {
        $this->setFlag(self::NO_WAIT, $confirm);
    }

    /**
     * @return boolean
     */
    public function isNoWait()
    {
        return $this->isFlagSet(self::NO_WAIT);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->connection->close();
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, ExchangeInterface $exchange, $routingKey = '')
    {
        if (!$exchange instanceof \Queue\Resources\Amqp\ExchangeInterface) {
            throw new InvalidResourceException(BaseDriver::AMQP);
        }

        $amqpMessage = new AMQPMessage($message->getBody(), $message->getAttributes());
        $this->getChannel()->basic_publish($amqpMessage, $exchange->getName(), $routingKey);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(QueueInterface $queue)
    {
        $message = $this->getChannel()->basic_get($queue->getName());
        if (!$message) {
            return null;
        }

        $id = null;
        if ($message->has('delivery_tag')) {
            $id = $message->get('delivery_tag');
        }
        return $this->getDriver()->createMessage($message->body, $message->get_properties(), $id);
    }

    /**
     * @return AMQPChannel
     */
    protected function getChannel()
    {
        if (!$this->channel) {
            $this->channel = $this->connection->channel();
        }
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function ack(MessageInterface $message)
    {
        $this->getChannel()->basic_ack($message->getUid());
    }

    /**
     * {@inheritdoc}
     */
    public function nack(MessageInterface $message)
    {
        if (!$message instanceof \Queue\Resources\Amqp\MessageInterface) {
            throw new InvalidResourceException(BaseDriver::AMQP);
        }

        $this->getChannel()->basic_nack($message->getUid(), false, $message->isRequeue());
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue(QueueInterface $queue)
    {
        if (!$queue instanceof \Queue\Resources\Amqp\QueueInterface) {
            throw new InvalidResourceException(BaseDriver::AMQP);
        }

        try {
            $this->getChannel()->queue_declare(
                $queue->getName(),
                $queue->isPassive(),
                $queue->isDurable(),
                $queue->isExclusive(),
                $queue->isAutoDelete(),
                $this->isNoWait(),
                $queue->getAttributes()
            );
        } catch (AMQPProtocolChannelException $e) {
            throw new BaseDriver\Exception\DivergentStructureException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(QueueInterface $queue)
    {
        $this->getChannel()->queue_delete($queue->getName(), false, false);
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange(ExchangeInterface $exchange)
    {
        if (!$exchange instanceof \Queue\Resources\Amqp\ExchangeInterface) {
            throw new InvalidResourceException(BaseDriver::AMQP);
        }

        try {
            $this->getChannel()->exchange_declare(
                $exchange->getName(),
                $exchange->getType(),
                $exchange->isPassive(),
                $exchange->isDurable(),
                $exchange->isAutoDelete(),
                $exchange->isInternal(),
                $this->isNoWait(),
                $exchange->getAttributes()
            );
        } catch (AMQPProtocolChannelException $e) {
            throw new BaseDriver\Exception\DivergentStructureException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExchange(ExchangeInterface $exchange)
    {
        $this->getChannel()->exchange_delete($exchange->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function bind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->getChannel()->queue_bind($queue->getName(), $exchange->getName(), $routingKey);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $this->getChannel()->queue_unbind($queue->getName(), $exchange->getName(), $routingKey);
    }
}

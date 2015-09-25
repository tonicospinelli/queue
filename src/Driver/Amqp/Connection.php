<?php

namespace Queue\Driver\Amqp;

use Queue\Driver\Exception\BindException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use Queue\AbstractProcess;
use Queue\ConfigurationInterface;
use Queue\ConsumerInterface;
use Queue\Driver\Exception\DivergentEntityException;
use Queue\Driver\MessageInterface;
use Queue\Entity\AbstractBindExchange;
use Queue\Entity\AbstractBindQueue;
use Queue\Entity\AbstractExchange;
use Queue\Entity\AbstractQueue;
use Queue\InterfaceQueue;
use Queue\Entity\AbstractBind as BindEntity;
use Queue\Entity\AbstractExchange as ExchangeEntity;
use Queue\Entity\AbstractQueue as QueueEntity;
use Queue\ProducerInterface;

class Connection implements \Queue\Driver\Connection
{
    /**
     * @var AMQPConnection
     */
    private $connection;
    /**
     * @var AMQPChannel
     */
    private $channel;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->connection = new AMQPConnection(
            $configuration->getHostname(),
            $configuration->getPort(),
            $configuration->getUsername(),
            $configuration->getPassword(),
            $configuration->getOption('vhost', '/'),
            $configuration->getOption('insist', false),
            $configuration->getOption('login_method', "AMQPLAIN"),
            $configuration->getOption('login_response', null),
            $configuration->getOption('locale', "en_US"),
            $configuration->getOption('connection_timeout', 3),
            $configuration->getOption('read_write_timeout', 3),
            $configuration->getOption('context', null),
            $configuration->getOption('keepalive', false),
            $configuration->getOption('heartbeat', 0)
        );
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
    public function prepare($message, array $properties = array(), $id = null)
    {
        return new Message($message, $properties, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, AbstractExchange $exchange)
    {
        $channel = $this->getChannel();
        $channel->basic_publish(Message::createAMQPMessage($message), $exchange->getExchangeName());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(AbstractQueue $queue)
    {
        $channel = $this->getChannel();
        $message = $channel->basic_get($queue->getQueueName());
        if (!$message) {
            return null;
        }

        return Message::create($message);
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
        $this->getChannel()->basic_ack($message->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function nack(MessageInterface $message)
    {
        $this->getChannel()->basic_nack($message->getId(), false, $message->isRequeue());
    }


    /**
     * {@inheritdoc}
     */
    public function createQueue(QueueEntity $queue)
    {
        $channel = $this->connection->channel();
        try {
            $channel->queue_declare($queue->getQueueName(), false, $queue->isDurable(), false, $queue->isAutoDelete(), false, $queue->getQueueArguments());
        } catch (AMQPProtocolChannelException $amqpException) {
            throw new DivergentEntityException('This Queue is different from servers', 0, $amqpException);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dropQueue(QueueEntity $queue)
    {
        $channel = $this->connection->channel();
        $channel->queue_delete($queue->getQueueName());
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange(ExchangeEntity $exchange)
    {
        $channel = $this->connection->channel();
        try {
            $channel->exchange_declare($exchange->getExchangeName(), $exchange->getType(), false, $exchange->isDurable(), $exchange->isAutoDelete(), $exchange->isInternal() , false, $exchange->getExchangeArguments());
        } catch (AMQPProtocolChannelException $amqpException) {
            throw new DivergentEntityException('This Exchange is different from servers', 0, $amqpException);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dropExchange(ExchangeEntity $exchange)
    {
        $channel = $this->connection->channel();
        $channel->exchange_delete($exchange->getExchangeName());
    }

    /**
     * {@inheritdoc}
     */
    public function createBind(BindEntity $bind)
    {
        $channel = $this->connection->channel();
        $exchangeName = $bind->getExchange()->getExchangeName();
        $routingKey = $bind->getRoutingKey();
        try {
            if($bind instanceof AbstractBindQueue) {
                $channel->queue_bind($bind->getTargetQueue()->getQueueName(), $exchangeName, $routingKey);
            } elseif ($bind instanceof AbstractBindExchange) {
                $channel->exchange_bind($bind->getTargetExchange()->getExchangeName(), $exchangeName, $routingKey);
            }
        } catch (AMQPProtocolChannelException $amqpException) {
            if ( $amqpException->getCode() == 404) {
                throw new BindException('Queue or Exchange not exist', 404, $amqpException);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dropBind(BindEntity $bind)
    {
        $channel = $this->connection->channel();
        $exchangeName = $bind->getExchange()->getExchangeName();
        $routingKey = $bind->getRoutingKey();
        if($bind instanceof AbstractBindQueue) {
            $channel->queue_unbind($bind->getTargetQueue()->getQueueName(), $exchangeName, $routingKey);
        } elseif ($bind instanceof AbstractBindExchange) {
            $channel->exchange_unbind($bind->getTargetExchange()->getExchangeName(), $exchangeName, $routingKey);
        }
    }
}

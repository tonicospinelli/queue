<?php

namespace Queue\Driver\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;
use Queue\AbstractQueue;
use Queue\ConfigurationInterface;
use Queue\ConsumerInterface;
use Queue\Driver\MessageInterface;
use Queue\InterfaceQueue;
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
    public function publish(MessageInterface $message, ProducerInterface $producer)
    {
        $this->declareQueue($producer);
        $channel = $this->getChannel();
        $channel->basic_publish(Message::createAMQPMessage($message), $producer->getWorkingExchangeName());
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(ConsumerInterface $consumer)
    {
        $this->declareQueue($consumer);
        $channel = $this->getChannel();

        $message = $channel->basic_get($consumer->getWorkingQueueName());

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

    protected function declareQueue(InterfaceQueue $queue)
    {
        $channel = $this->getChannel();
        $channel->queue_declare(
            $queue->getWorkingQueueName(),
            AbstractQueue::QUEUE_NOT_PASSIVE,
            AbstractQueue::QUEUE_DURABLE,
            AbstractQueue::QUEUE_NOT_EXCLUSIVE,
            AbstractQueue::QUEUE_NOT_AUTO_DELETE,
            AbstractQueue::QUEUE_NO_WAIT,
            $queue->getQueueArguments()
        );
        $exchange = $queue->getExchange();
        $channel->exchange_declare(
            $queue->getWorkingExchangeName(),
            $exchange->getChannel(),
            $exchange->isPassive(),
            $exchange->isDurable(),
            $exchange->isAutoDelete(),
            $exchange->isInternal(),
            $exchange->isNoWait(),
            $exchange->getArguments(),
            $exchange->getTickets()
        );
        $channel->queue_bind($queue->getWorkingQueueName(), $queue->getWorkingExchangeName());
    }

    /**
     * {@inheritdoc}
     */
    public function getExchange()
    {
        return new AmqpExchange();
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
}

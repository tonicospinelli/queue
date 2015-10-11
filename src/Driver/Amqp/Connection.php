<?php

namespace Queue\Driver\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use Queue\Component\BitwiseFlag;
use Queue\ConfigurationInterface;
use Queue\Driver\Exception\DivergentStructureException;
use Queue\Resources\Amqp\Message;
use Queue\Resources\MessageInterface;
use Queue\Resources\Queue;
use Queue\Resources\Tunnel;

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

    public function __construct(ConfigurationInterface $configuration)
    {
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
    public function prepare($message, array $properties = array(), $id = null)
    {
        return new Message($message, $properties, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, Tunnel $tunnel, $patternKey = '')
    {
        $channel = $this->getChannel();
        $channel->basic_publish(Message::createAMQPMessage($message), $tunnel->getName(), $patternKey);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne($queueName)
    {
        $channel = $this->getChannel();
        $message = $channel->basic_get($queueName);
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
     * @param \Queue\Resources\Amqp\Queue $queue
     */
    public function createQueue(Queue $queue)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare(
            $queue->getName(),
            $queue->isPassive(),
            $queue->isDurable(),
            $queue->isExclusive(),
            $queue->isAutoDelete(),
            $this->isNoWait(),
            $queue->getAttributes()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(Queue $queue)
    {
        $this->connection->channel()->queue_delete($queue->getName(), false, false);
    }

    /**
     * {@inheritdoc}
     * @param \Queue\Resources\Amqp\Tunnel $tunnel
     */
    public function createTunnel(Tunnel $tunnel)
    {
        $channel = $this->connection->channel();
        $channel->exchange_declare(
            $tunnel->getName(),
            $tunnel->getType(),
            $tunnel->isPassive(),
            $tunnel->isDurable(),
            $tunnel->isAutoDelete(),
            $tunnel->isInternal(),
            $this->isNoWait(),
            $tunnel->getAttributes()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTunnel(Tunnel $tunnel)
    {
        $channel = $this->connection->channel();
        $channel->exchange_delete($tunnel->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function bind($queue, $tunnel, $routeKey = '')
    {
        $channel = $this->connection->channel();
        $channel->queue_bind($queue, $tunnel, $routeKey);
    }

    /**
     * {@inheritdoc}
     */
    public function unbind($queue, $tunnel, $routeKey = '')
    {
        $channel = $this->connection->channel();
        $channel->queue_unbind($queue, $tunnel, $routeKey);
    }
}

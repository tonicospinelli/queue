<?php

namespace Queue\Driver\Redis;

use Predis\Client;
use Queue\ConfigurationInterface;
use Queue\Driver as BaseDriver;
use Queue\Resources\ExchangeInterface;
use Queue\Resources\MessageInterface;
use Queue\Resources\QueueInterface;

class Connection implements \Queue\Driver\Connection
{
    const KEY_SEPARATOR = ':';
    const KEY_ROUTE = 'route';
    const KEY_QUEUE = 'queue';
    const KEY_EXCHANGE = 'exchange';
    const KEY_BIND = 'bind';
    const KEY_MESSAGE = 'message';
    const KEY_PREFIX = 'default';

    /**
     * @var Client
     */
    private $connection;

    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var string
     */
    private $keyPrefix;

    /**
     * @param ConfigurationInterface $configuration
     * @param Driver $driver
     */
    public function __construct(ConfigurationInterface $configuration, Driver $driver)
    {
        $this->driver = $driver;
        $this->connection = new Client(
            [
                'scheme' => $configuration->getOption('scheme', 'tcp'),
                'host' => $configuration->getHostname(),
                'port' => $configuration->getPort(),
                'timeout' => $configuration->getOption('timeout', 5.0),
            ]
        );
        $this->keyPrefix = $configuration->getOption('key_prefix', self::KEY_PREFIX);
    }

    /**
     * @param string $queueName
     * @return QueueInterface
     * @throws RedisException
     */
    private function getQueue($queueName)
    {
        $queueKey = $this->formatKey(self::KEY_QUEUE, $queueName);
        if (!$this->getConnection()->exists($queueKey)) {
            throw new RedisException('Queue ' . $queueName . ' not found');
        }

        $rawQueue = $this->getConnection()->get($queueKey);
        $queue = unserialize($rawQueue);
        return $queue;
    }

    /**
     * @param MessageInterface $message
     * @param QueueInterface $queue
     * @return MessageInterface
     */
    private function unacked(MessageInterface $message, QueueInterface $queue)
    {
        $uid = $message->getUid() ?: md5(uniqid(rand(), true));

        $message = $this->getDriver()->createMessage($message->getBody(), $message->getAttributes(), $uid);

        $messageKey = $this->formatKey(self::KEY_MESSAGE, $message->getUid());
        $this->getConnection()->set($messageKey, serialize($message));

        $queueKey = $this->formatKey(self::KEY_QUEUE, $queue->getName(), 'unacked');
        $this->getConnection()->rpush($queueKey, $message->getUid());

        return $message;
    }

    /**
     * Delete message from unacked session
     * @param MessageInterface $message
     */
    private function deleteMessage(MessageInterface $message)
    {
        $queueKey = $this->formatKey(self::KEY_QUEUE, $message->getAttribute(self::KEY_QUEUE), 'unacked');
        $this->getConnection()->lrem($queueKey, 0, $message->getUid());

        $messageKey = $this->formatKey(self::KEY_MESSAGE, $message->getUid());
        $this->getConnection()->del($messageKey);
    }

    /**
     * @param string $type
     * @param string $name
     * @return string
     */
    private function formatKey($type, $name)
    {
        $names = array_merge([$this->keyPrefix], func_get_args());

        $args = array_filter($names, function ($arg) {
            return is_null($arg) || !empty($arg);
        });

        return implode(self::KEY_SEPARATOR, $args);
    }

    /**
     * @param string $queueName
     * @param MessageInterface $message
     */
    private function send($queueName, MessageInterface $message)
    {
        $queueKey = $this->formatKey(self::KEY_QUEUE, $queueName);

        $this->getConnection()->rpush($queueKey, serialize($message));
    }

    /**
     * @return Client
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverName()
    {
        return $this->getDriver()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->getConnection()->disconnect();
    }

    /**
     * {@inheritdoc}
     */
    public function publish(MessageInterface $message, ExchangeInterface $exchange, $routingKey = '')
    {
        foreach ($exchange->getBindings() as $routeName => $queues) {
            $isEmptyRoute = (empty($routeName) && empty($routingKey));
            $isRouteMatched = !preg_match('/' . $routeName . '/', $routingKey);
            if (!$isEmptyRoute && $isRouteMatched) {
                continue;
            }
            foreach ($queues as $queueName) {
                $queue = $this->getQueue($queueName);
                $attributes = [
                    self::KEY_QUEUE => $queue->getName(),
                    self::KEY_EXCHANGE => $exchange->getName(),
                    self::KEY_ROUTE => $routingKey,
                ];
                $payload = $this->getDriver()->createMessage($message->getBody(), $attributes, $message->getUid());
                $this->send($queue->getName(), $payload);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fetchOne(QueueInterface $queue)
    {
        $queueKey = $this->formatKey(self::KEY_QUEUE, $queue->getName());
        $envelope = $this->getConnection()->lpop($queueKey);

        if (!$envelope) {
            return null;
        }

        $message = $this->unacked(unserialize($envelope), $queue);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function ack(MessageInterface $message)
    {
        $this->deleteMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public function nack(MessageInterface $message)
    {
        $this->deleteMessage($message);
        if ($message->isRequeue()) {
            $queueKey = $this->formatKey(self::KEY_QUEUE, $message->getAttribute(self::KEY_QUEUE));
            $message->setRequeue(false);
            $this->send($queueKey, $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createQueue(QueueInterface $queue)
    {
        $queueKey = $this->formatKey(self::KEY_QUEUE, $queue->getName());
        $this->getConnection()->set($queueKey, serialize($queue));
    }

    /**
     * {@inheritdoc}
     */
    public function deleteQueue(QueueInterface $queue)
    {
        $queueKey = $this->formatKey(self::KEY_QUEUE, $queue->getName());
        $this->getConnection()->del($queueKey);
    }

    /**
     * {@inheritdoc}
     */
    public function createExchange(ExchangeInterface $exchange)
    {
        $exchangeKey = $this->formatKey(self::KEY_EXCHANGE, $exchange->getName());
        $this->getConnection()->set($exchangeKey, serialize($exchange));
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExchange(ExchangeInterface $exchange)
    {
        $exchangeKey = $this->formatKey(self::KEY_EXCHANGE, $exchange->getName());
        $this->getConnection()->del($exchangeKey);
    }

    /**
     * {@inheritdoc}
     */
    public function bind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $exchangeKey = $this->formatKey(self::KEY_BIND, $queue->getName(), $exchange->getName(), $routingKey);

        $queues = array();
        if ($this->getConnection()->exists($exchangeKey)) {
            $queues = $this->getConnection()->lrange($exchangeKey, 0, -1);
        }

        if (!in_array($queue->getName(), $queues)) {
            $this->getConnection()->rpush($exchangeKey, array($queue->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unbind(QueueInterface $queue, ExchangeInterface $exchange, $routingKey = '')
    {
        $exchangeKey = $this->formatKey(self::KEY_BIND, $queue->getName(), $exchange->getName(), $routingKey);
        $queues = array();
        if ($this->getConnection()->exists($exchangeKey)) {
            $queues = $this->getConnection()->lrange($exchangeKey, 0, -1);
        }

        $queues = array_filter($queues, function ($queueName) use ($queue) {
            return $queueName != $queue->getName();
        });

        $this->getConnection()->set($exchangeKey, $queues);
    }
}

# Queue
A PHP Lib for Handle Queue and keep it simple to publish and consume messages.

### Setup

```
$ git clone git@github.com:tricae-br/queue.git
$ cd queue/
$ composer install
```

### Tests

You must be install RabbitMQ to run integration tests.

```
$ phpunit -c phpunit.xml.dist
```

### Prepare environment

```php
// bootstrap.php

require_once __DIR__ . '/vendor/autoload.php';

$configuration = new \Queue\Configuration(\Queue\Driver::AMQP, '127.0.0.1', 5672, 'guest', 'guest');

$connection = \Queue\DriverManager::getConnection($configuration);

$queue = $connection->getDriver()->createQueue('logs.error');
$exchange = $connection->getDriver()->createExchange('logs.error', \Queue\Resources\Exchange::TYPE_DIRECT);
$exchange->addBinding($queue->getName(), 'error');

$connection->createQueue($queue);
$connection->createExchange($exchange);
$connection->bind($queue, $exchange, 'error');
```

### How to publish a Message

```php
// publisher.php

require_once __DIR__ . '/bootstrap.php';

class DummyProducer extends \Queue\Producer
{
}

$producer = new DummyProducer($connection, $exchange);

if (empty($argv[1])) {
    throw new InvalidArgumentException('message not found to publish');
}

$message = $producer->prepare($argv[1]);

$producer->publish($message, 'error');

$connection->close();
```

### How to Consume Messages

```php
// consumer.php

require_once __DIR__ . '/bootstrap.php';

class EchoConsumer extends \Queue\Consumer
{
    public function process(\Queue\Resources\MessageInterface $message)
    {
        echo $message->getBody() . PHP_EOL;
        $message->setAck();
    }
}

$consumer = new EchoConsumer($connection, $queue);

$consumer->consume();

$connection->close();
```

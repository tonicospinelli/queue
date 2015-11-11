# Queue
A PHP Lib for Handle Queue 

### Setup

```
$ git clone git@github.com:tricae-br/queue.git
$ cd queue/
$ composer install
```

### Tests

```
$ cd tests/
$ cp config.php.dist config.php
$ phpunit .
```
### Prepare environment

```php
// bootstrap.php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/tests/config.php';

$configuration = new \Queue\Configuration(\Queue\Driver::AMQP, RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD);

$connection = \Queue\DriverManager::getConnection($configuration);

$queue = $connection->getDriver()->createQueue('logs.error');
$exchange = $connection->getDriver()->createExchange('logs.error', \Queue\Resources\Exchange::TYPE_DIRECT, array());
$exchange->addBinding($queue->getName(), 'error');

$connection->createQueue($queue);
$connection->createExchange($exchange);
$connection->bind($queue, $exchange, 'error');
```

### How to publish a Message

```php
// publisher.php

require_once __DIR__ . '/bootstrap.php';

$producer = new \QueueTest\Mocks\Producer\ProducerMock($connection, $exchange);

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

$consumer = new \QueueTest\Mocks\Consumer\ConsumerMock($connection, $queue);

$consumer->consume();

$connection->close();
```

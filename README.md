# Queue
A PHP Lib for Handle Queue 


### How to publish a Message

```php

require_once __DIR__ . '/vendor/autoload.php';

use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;
use QueueTest\Fake\ProducerFake;

$configuration = new Configuration(Driver::AMQP, 'host', 5672, 'user', 'pass');

$connection = DriverManager::getConnection($configuration);

$queue = new ProducerFake($connection);

$message = $queue->prepare(123);

$queue->publish($message);
```

### How to Consume Messages

```php
require_once __DIR__ . '/vendor/autoload.php';

use Queue\Configuration;
use Queue\Driver;
use Queue\DriverManager;
use QueueTest\Fake\ConsumerFake;

$connection = DriverManager::getConnection(
    new Configuration(Driver::AMQP, 'host', 5672, 'user', 'pass')
);

$queue = new ConsumerFake($connection, ConsumerFake::PERSISTENT);

$queue->consume();
```

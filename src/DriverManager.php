<?php

namespace Queue;

use Queue\Driver\Exception\InvalidDriverException;
use Queue\Driver\Exception\UnknownDriverException;
use Queue\Exception\InvalidWrapperClassException;

class DriverManager
{
    private static $drivers = array(
        Driver::AMQP => 'Queue\Driver\Amqp\Driver',
        Driver::IN_MEMORY => 'Queue\Driver\InMemory\Driver',
    );

    /**
     * Private constructor. This class cannot be instantiated.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param string $driverName
     * @return string
     * @throws UnknownDriverException
     */
    private static function getClassName($driverName)
    {
        if (!isset(self::$drivers[$driverName])) {
            throw new UnknownDriverException($driverName, self::getAvailableDrivers());
        }
        return self::$drivers[$driverName];
    }

    /**
     * Returns the list of supported drivers.
     *
     * @return array List of supported drivers.
     */
    public static function getAvailableDrivers()
    {
        return array_keys(self::$drivers);
    }

    /**
     * Adds a new supported driver.
     *
     * @param string $name Driver's name
     * @param string $className Class name of driver
     * @return void
     * @throws InvalidDriverException
     */
    public static function addAvailableDriver($name, $className)
    {
        $ref = new \ReflectionClass($className);
        if (!$ref->isSubclassOf('\Queue\Driver')) {
            throw new InvalidDriverException($className);
        }
        self::$drivers[$name] = $ref->getName();
    }

    /**
     * @param ConfigurationInterface $configuration
     * @return Connection
     * @throws InvalidWrapperClassException
     */
    public static function getConnection(ConfigurationInterface $configuration)
    {
        $driverClassName = static::getClassName($configuration->getDriver());

        $driver = new $driverClassName();

        $wrapperClass = $configuration->getOption('wrapperClass', '\Queue\Connection');
        if (!is_subclass_of($wrapperClass, '\Queue\Driver\Connection')) {
            throw new InvalidWrapperClassException($wrapperClass);
        }

        return new $wrapperClass($configuration, $driver);
    }
}

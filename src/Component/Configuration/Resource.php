<?php

namespace Queue\Component\Configuration;

use Queue\Driver;
use Queue\DriverManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Resource implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $driver;

    private static $availableQueueDefinitions = array(
        Driver::AMQP => 'Queue\Component\Configuration\Definition\Amqp\QueueSection',
    );
    private static $availableExchangeDefinitions = array(
        Driver::AMQP => 'Queue\Component\Configuration\Definition\Amqp\ExchangeSection',
    );

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $driver
     * @return string
     * @throws Driver\Exception\UnknownDriverException
     */
    public static function getQueueDefinition($driver)
    {
        if (!self::$availableQueueDefinitions[$driver]) {
            throw new Driver\Exception\UnknownDriverException($driver, DriverManager::getAvailableDrivers());
        }
        return self::$availableQueueDefinitions[$driver];
    }

    /**
     * @param string $driver
     * @return string
     * @throws Driver\Exception\UnknownDriverException
     */
    public static function getExchangeDefinition($driver)
    {
        if (!self::$availableExchangeDefinitions[$driver]) {
            throw new Driver\Exception\UnknownDriverException($driver, DriverManager::getAvailableDrivers());
        }
        return self::$availableExchangeDefinitions[$driver];
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('resources');

        $this->addClassesSection($rootNode);
        $this->addQueueSection($rootNode);
        $this->addExchangeSection($rootNode);
        return $treeBuilder;
    }

    private function addClassesSection(ArrayNodeDefinition $parent)
    {
        $driver = ucfirst($this->driver);
        $parent
            ->children()
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('queue')->defaultValue("Queue\\Resources\\{$driver}\\Queue")->end()
                        ->scalarNode('exchange')->defaultValue("Queue\\Resources\\{$driver}\\Exchange")->end()
                    ->end()
            ->end()
        ;
    }

    private function addQueueSection(ArrayNodeDefinition $parentNode)
    {
        $classDefinition = self::getQueueDefinition($this->driver);
        $ref = new \ReflectionClass($classDefinition);
        $ref->newInstance('queues', $parentNode);
    }

    private function addExchangeSection(ArrayNodeDefinition $parentNode)
    {
        $classDefinition = self::getExchangeDefinition($this->driver);
        $ref = new \ReflectionClass($classDefinition);
        $ref->newInstance('exchanges', $parentNode);
    }
}

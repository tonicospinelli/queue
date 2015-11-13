<?php

namespace Queue\Component;

use Queue\Component\Configuration\Resource;
use Queue\Component\Configuration\YamlFileLoader;
use Queue\ConfigurationInterface;
use Queue\Driver\Connection;
use Queue\ResourceManager;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class Setup
{
    /**
     * Creates a resource with a yaml loader.
     *
     * @param string $path
     *
     * @param Connection $connection
     * @return ResourceManager
     */
    public static function createYAMLResource($path, Connection $connection)
    {
        $locator = new FileLocator(array(dirname($path)));

        $loader = new YamlFileLoader($locator);
        $configValues = $loader->load($path);

        $definition = new Resource($connection->getDriverName());
        $processor = new Processor();
        $processedConfig = $processor->processConfiguration($definition, $configValues);

        $resourceManager = ResourceManager::createFromConfiguration($processedConfig);

        return $resourceManager;
    }
}

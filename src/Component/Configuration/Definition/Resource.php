<?php

namespace Queue\Component\Configuration\Definition;

use Queue\Driver;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Resource implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $driver;

    public function __construct($driver)
    {
        $this->driver = $driver;
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

        $rootNode->append($this->addQueueNode());
        $rootNode->append($this->addTunnelNode());
        $rootNode->append($this->addBindingNode());
        return $treeBuilder;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addQueueNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('queues');
        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('class')->defaultValue('\Queue\Resources\Queue')->end()
                    ->scalarNode('durable')->defaultFalse()->end()
                    ->scalarNode('auto_delete')->defaultFalse()->end()
                    ->scalarNode('passive')->defaultFalse()->end()
                    ->append($this->addAttributesNode())
                ->end()
            ->end()
        ;
        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addTunnelNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('tunnels');
        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('class')->defaultValue('\Queue\Resources\Tunnel')->end()
                    ->scalarNode('type')->defaultValue('direct')->end()
                    ->scalarNode('durable')->defaultFalse()->end()
                    ->scalarNode('auto_delete')->defaultTrue()->end()
                    ->scalarNode('passive')->defaultFalse()->end()
                    ->append($this->addAttributesNode())
                ->end()
            ->end();
        return $node;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addBindingNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bindings');
        $rootNode
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('class')->defaultValue('\Queue\Resources\Binding')->end()
                    ->scalarNode('tunnel')->isRequired()->end()
                    ->scalarNode('queue')->isRequired()->end()
                    ->arrayNode('pattern_key')
                        ->requiresAtLeastOneElement()
                        ->prototype('scalar')
                    ->end()
                ->end()
            ->end();
        return $rootNode;
    }

    /**
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition|\Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addAttributesNode()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('attributes');
        $rootNode
            ->prototype('scalar')
            ->end();
        return $rootNode;
    }
}

<?php

namespace Queue\Component\Configuration\Definition\Amqp;

use Queue\Component\Configuration\Definition\Section;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ExchangeSection extends Section
{
    public function defineNodeStructure(ArrayNodeDefinition $parent)
    {
        $parent
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode($this->name)
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('type')->cannotBeEmpty()->end()
                        ->scalarNode('durable')->defaultFalse()->end()
                        ->scalarNode('auto_delete')->defaultTrue()->end()
                        ->scalarNode('passive')->defaultFalse()->end()
                        ->scalarNode('internal')->defaultFalse()->end()
                        ->arrayNode('attributes')
                            ->normalizeKeys(false)
                            ->children()
                                ->scalarNode('alternate-exchange')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('bindings')
                            ->normalizeKeys(false)
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                            ->children()
                                ->arrayNode('queues')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array(''))
                                ->end()
                            ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

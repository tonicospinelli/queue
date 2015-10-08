<?php

namespace Queue\Component\Configuration\Definition\Amqp;

use Queue\Component\Configuration\Definition\Section;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class QueueSection extends Section
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
                        ->scalarNode('durable')->defaultFalse()->end()
                        ->scalarNode('auto_delete')->defaultTrue()->end()
                        ->scalarNode('exclusive')->defaultFalse()->end()
                        ->scalarNode('passive')->defaultFalse()->end()
                        ->arrayNode('attributes')
                            ->normalizeKeys(false)
                            ->children()
                                ->integerNode('x-message-ttl')->defaultNull()->end()
                                ->integerNode('x-expires')->defaultNull()->end()
                                ->integerNode('x-max-length')->defaultNull()->end()
                                ->integerNode('x-max-length-bytes')->defaultNull()->end()
                                ->scalarNode('x-dead-letter-exchange')->defaultNull()->end()
                                ->scalarNode('x-dead-letter-routing-key')->defaultNull()->end()
                                ->integerNode('x-max-priority')->defaultNull()->end()
                            ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

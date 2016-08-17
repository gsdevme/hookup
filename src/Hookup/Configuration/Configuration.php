<?php

namespace Hookup\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hookup');

        $rootNode
            ->children()
                ->arrayNode('github')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('token')->isRequired()->end()
                        ->arrayNode('repositories')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('user')->defaultNull()->end()
                ->arrayNode('servers')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('hostname')->isRequired()->end()
                            ->scalarNode('user')->defaultNull()->end()
                            ->integerNode('port')->defaultValue(22)->end()
                            ->scalarNode('identity_file')->defaultNull()->end()
                            ->scalarNode('proxy_command')->defaultNull()->end()
                            ->scalarNode('local_forward')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

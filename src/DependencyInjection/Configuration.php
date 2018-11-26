<?php

namespace BenTools\DoctrineWatcherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('doctrine_watcher');
        $rootNode
            ->children()

                ->arrayNode('options')
                    ->children()
                        ->booleanNode('trigger_on_persist')->defaultFalse()->end()
                        ->booleanNode('trigger_when_no_changes')->defaultFalse()->end()
                    ->end()
                ->end()

                ->arrayNode('watch')
                    ->useAttributeAsKey('entity_class')
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('trigger_on_persist')->defaultFalse()->end()
                            ->booleanNode('trigger_when_no_changes')->defaultFalse()->end()
                            ->arrayNode('properties')
                                ->useAttributeAsKey('property')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('callback')->end()
                                        ->booleanNode('trigger_on_persist')->defaultFalse()->end()
                                        ->booleanNode('trigger_when_no_changes')->defaultFalse()->end()
                                        ->booleanNode('iterable')->defaultFalse()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;


        return $treeBuilder;
    }
}

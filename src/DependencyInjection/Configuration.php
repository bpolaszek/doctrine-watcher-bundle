<?php

namespace BenTools\DoctrineWatcherBundle\DependencyInjection;

use BenTools\DoctrineWatcher\Changeset\PropertyChangeset;
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
                        ->scalarNode('type')->defaultValue(PropertyChangeset::CHANGESET_DEFAULT)->end()
                        ->booleanNode('trigger_on_persist')->defaultTrue()->end()
                        ->booleanNode('trigger_when_no_changes')->defaultTrue()->end()
                    ->end()
                ->end()

                ->arrayNode('watch')
                    ->useAttributeAsKey('entity_class')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('options')
                                ->children()
                                    ->scalarNode('type')->defaultValue(PropertyChangeset::CHANGESET_DEFAULT)->end()
                                    ->booleanNode('trigger_on_persist')->defaultTrue()->end()
                                    ->booleanNode('trigger_when_no_changes')->defaultTrue()->end()
                                ->end()
                            ->end()
                            ->arrayNode('properties')
                                ->useAttributeAsKey('property')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('callback')->end()
                                            ->arrayNode('options')
                                                ->children()
                                                    ->scalarNode('type')->defaultValue(PropertyChangeset::CHANGESET_DEFAULT)->end()
                                                    ->booleanNode('trigger_on_persist')->defaultTrue()->end()
                                                    ->booleanNode('trigger_when_no_changes')->defaultTrue()->end()
                                                ->end()
                                            ->end()
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

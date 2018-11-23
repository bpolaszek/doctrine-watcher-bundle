<?php

namespace BenTools\DoctrineWatcherBundle\DependencyInjection;

use BenTools\DoctrineWatcher\Changeset\PropertyChangeset;
use BenTools\DoctrineWatcher\Watcher\DoctrineWatcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DoctrineWatcherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator([__DIR__ . '/../Resources/config/']));
        $loader->load('services.xml');

        self::loadConfiguration($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public static function loadConfiguration(array $config, ContainerBuilder $container): void
    {
        $watcherDefinition = $container->findDefinition(DoctrineWatcher::class);
        if (isset($config['options'])) {
            $watcherDefinition->setArgument('options', $config['options']);
        }

        foreach ($config['watch'] ?? [] as $entityClass => $entityConfig) {
            foreach ($entityConfig['properties'] as $property => $propertyConfig) {
                $propertyOptions = $propertyConfig['options'] ?? [];
                if (isset($propertyConfig['iterable'])) {
                    $propertyOptions['type'] = $propertyConfig['iterable'] ? PropertyChangeset::CHANGESET_ITERABLE : PropertyChangeset::CHANGESET_DEFAULT;
                }

                $callback = explode('::', $propertyConfig['callback']);
                $callback[1] = $callback[1] ??'__invoke';
                $callback[0] = new Reference($callback[0]);

                $watcherDefinition->addMethodCall('watch', [
                    $entityClass,
                    $property,
                    $callback,
                    $propertyOptions
                ]);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'doctrine_watcher';
    }
}
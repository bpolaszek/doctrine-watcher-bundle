<?php

namespace BenTools\DoctrineWatcherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DoctrineWatcherCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $tagged = $container->findTaggedServiceIds('bentools.doctrine_watcher');

        $config = [];
        foreach ($tagged as $serviceId => $tags) {
            foreach ($tags as $data) {
                if (!isset($data['entity_class'])) {
                    throw new \InvalidArgumentException('Config key "entity_class" is required.');
                }

                if (!isset($data['property'])) {
                    throw new \InvalidArgumentException('Config key "property" is required.');
                }

                $data['method'] = $data['method'] ?? '__invoke';
                $data['callback'] = sprintf('%s::%s', $serviceId, $data['method']);

                $propertyConfig = [
                    'callback' => $data['callback'],
                    'iterable' => $data['iterable'] ?? null,
                    'trigger_on_persist' => $data['trigger_on_persist'] ?? null,
                    'trigger_when_no_changes' => $data['trigger_when_no_changes'] ?? null,
                ];

                $config['watch'][$data['entity_class']]['properties'][$data['property']] = $propertyConfig;
            }
        }

        if (!empty($config)) {
            DoctrineWatcherExtension::loadConfiguration($config, $container);
        }
    }
}

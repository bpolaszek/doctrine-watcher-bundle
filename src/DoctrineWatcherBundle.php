<?php

namespace BenTools\DoctrineWatcherBundle;

use BenTools\DoctrineWatcherBundle\DependencyInjection\DoctrineWatcherCompilerPass;
use BenTools\DoctrineWatcherBundle\DependencyInjection\DoctrineWatcherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DoctrineWatcherBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function getContainerExtension()
    {
        return new DoctrineWatcherExtension();
    }

    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DoctrineWatcherCompilerPass());
    }
}

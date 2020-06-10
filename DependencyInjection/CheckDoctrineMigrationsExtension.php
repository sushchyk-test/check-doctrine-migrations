<?php

declare(strict_types=1);

namespace Alabama\CheckDoctrineMigrations\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * DoctrineMigrationsExtension.
 */
class CheckDoctrineMigrationsExtension extends Extension
{
    /**
     * Responds to the check migrations configuration parameter.
     *
     * @param string[][] $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config/');
        $loader  = new XmlFileLoader($container, $locator);

        $loader->load('services.xml');
    }
}

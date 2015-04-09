<?php

namespace Symbio\OrangeGate\TranslationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SymbioOrangeGateTranslationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('admin.yml');

        $container->setParameter('orangegate_translation.locales', $config['locales']);
        $container->setParameter('orangegate_translation.default_locale', $container->getParameter('kernel.default_locale', 'en'));
        $container->setParameter('orangegate_translation.required_locales', $config['required_locales']);
        $container->setParameter('orangegate_translation.templating', $config['templating']);

        $container->setAlias('orangegate_translation.manager_registry', $config['manager_registry']);
    }
}

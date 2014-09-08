<?php

namespace Symbio\OrangeGate\PageBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymbioOrangeGatePageExtension extends Extension implements PrependExtensionInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepend(ContainerBuilder $container)
	{
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['SonataPageBundle'])) {
            $container->setParameter('sonata.page.admin.page.class', 'Symbio\OrangeGate\PageBundle\Admin\PageAdmin');
        }
	}
}
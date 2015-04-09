<?php

namespace Symbio\OrangeGate\TranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder;

class TemplatingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false !== ($template = $container->getParameter('orangegate_translation.templating'))) {
            $resources = $container->getParameter('twig.form.resources');

            if (!in_array($template, $resources)) {
                $resources[] = $template;
                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}

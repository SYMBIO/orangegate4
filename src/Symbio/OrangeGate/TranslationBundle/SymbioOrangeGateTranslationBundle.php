<?php

namespace Symbio\OrangeGate\TranslationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symbio\OrangeGate\TranslationBundle\DependencyInjection\Compiler\TemplatingPass;

class SymbioOrangeGateTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplatingPass());
    }
}

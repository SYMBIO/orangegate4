<?php

namespace Symbio\OrangeGate\MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symbio\OrangeGate\MediaBundle\DependencyInjection\Compiler\AddProviderCompilerPass;

class SymbioOrangeGateMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddProviderCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'CoopTilleulsCKEditorSonataMediaBundle';
    }
}

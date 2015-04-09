<?php

namespace Symbio\OrangeGate\DoctrineORMAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symbio\OrangeGate\DoctrineORMAdminBundle\DependencyInjection\Compiler\AddAuditEntityCompilerPass;

class SymbioOrangeGateDoctrineORMAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataDoctrineORMAdminBundle';
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddAuditEntityCompilerPass());
    }}

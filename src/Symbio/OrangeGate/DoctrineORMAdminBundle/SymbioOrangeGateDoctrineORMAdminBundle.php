<?php

namespace Symbio\OrangeGate\DoctrineORMAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymbioOrangeGateDoctrineORMAdminBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataDoctrineORMAdminBundle';
    }
}

<?php

namespace Symbio\OrangeGate\SeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymbioOrangeGateSeoBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataSeoBundle';
    }
}
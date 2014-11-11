<?php

namespace Symbio\OrangeGate\MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymbioOrangeGateMediaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'CoopTilleulsCKEditorSonataMediaBundle';
    }
}
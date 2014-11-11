<?php

namespace Symbio\OrangeGate\TimelineBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymbioOrangeGateTimelineBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataTimelineBundle';
    }
}
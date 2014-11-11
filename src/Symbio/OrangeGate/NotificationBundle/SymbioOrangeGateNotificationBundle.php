<?php

namespace Symbio\OrangeGate\NotificationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymbioOrangeGateNotificationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataNotificationBundle';
    }
}
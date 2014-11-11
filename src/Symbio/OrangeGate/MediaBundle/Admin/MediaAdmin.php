<?php

namespace Symbio\OrangeGate\MediaBundle\Admin;

use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;

class MediaAdmin extends \Sonata\MediaBundle\Admin\ORM\MediaAdmin
{
   /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('list'))) {
            return;
        }

        foreach ($this->pool->getContexts() as $context => $options) {
            $menu->addChild(
                $this->trans('sidemenu.link_context_'.$context, array(), 'SymbioOrangeGateMediaBundle'),
                array('uri' => $this->generateUrl('list', array('context' => $context, 'category' => null, 'hide_context' => null)))
            );
        }
    }
}
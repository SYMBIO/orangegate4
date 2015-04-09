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

    public function getPersistentParameters()
    {
        $parameters = parent::getPersistentParameters();

        if (!$this->hasRequest()) {
            return $parameters;
        }

        if ($filter = $this->getRequest()->get('filter')) {
            $context = $filter['context']['value'];
        } else {
            $context   = $this->getRequest()->get('context', $this->pool->getDefaultContext());
        }

        $providers = $this->pool->getProvidersByContext($context);
        $provider  = $this->getRequest()->get('provider');

        // if the context has only one provider, set it into the request
        // so the intermediate provider selection is skipped
        if (count($providers) == 1 && null === $provider) {
            $provider = array_shift($providers)->getName();
            $this->getRequest()->query->set('provider', $provider);
        }

        $categoryId = $this->getRequest()->get('category');

        if (!$categoryId) {
            $categoryId = $this->categoryManager->getRootCategory($context)->getId();
        }

        return array_merge($parameters,array(
            'provider'     => $provider,
            'context'      => $context,
            'category'     => $categoryId,
            'hide_context' => (bool)$this->getRequest()->get('hide_context')
        ));
    }
}

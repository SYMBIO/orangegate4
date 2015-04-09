<?php

namespace Symbio\OrangeGate\PageBundle\Controller;

use Sonata\PageBundle\Controller\BlockAdminController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Block Admin Controller
 */
class BlockAdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return parent::listAction();
    }
}

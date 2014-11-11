<?php

namespace Symbio\OrangeGate\AdminBundle\Util;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\NoAceFoundException;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Sonata\AdminBundle\Util\AdminObjectAclData;
use Sonata\AdminBundle\Util\AdminObjectAclManipulator as BaseAdminObjectAclManipulator;

class AdminObjectAclManipulator extends BaseAdminObjectAclManipulator
{
    public function createForm(AdminObjectAclData $data)
    {
        // Retrieve object identity
        $objectIdentity = ObjectIdentity::fromDomainObject($data->getObject());
        $acl = $data->getSecurityHandler()->getObjectAcl($objectIdentity);
        if (!$acl) {
            $acl = $data->getSecurityHandler()->createAcl($objectIdentity);
        }

        $data->setAcl($acl);

        $masks = $data->getMasks();

        // Create a form to set ACL
        $formBuilder = $this->formFactory->createBuilder('form');
        foreach ($data->getAclUsers() as $aclUser) {
            $securityIdentity = UserSecurityIdentity::fromAccount($aclUser);

            foreach ($data->getUserPermissions() as $permission) {
                try {
                    $checked = $acl->isGranted(array($masks[$permission]), array($securityIdentity));
                } catch (NoAceFoundException $e) {
                    $checked = false;
                }

                $formBuilder->add($aclUser->getId() . $permission, 'checkbox', array('required' => false, 'data' => $checked, 'label' => ' '));
            }
        }

        $form = $formBuilder->getForm();
        $data->setForm($form);

        return $form;
    }
}

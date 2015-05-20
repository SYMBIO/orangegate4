<?php

namespace Symbio\OrangeGate\MediaBundle\Controller;

use CoopTilleuls\Bundle\CKEditorSonataMediaBundle\Controller\MediaAdminController as Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MediaAdminController extends Controller
{
    /**
     * Edit action
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function editAction($id = null, Request $request = null)
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->getRestMethod() == 'POST') {
            $form->submit($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {

                try {
                    $object = $this->admin->update($object);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                            'result'    => 'ok',
                            'objectId'  => $this->admin->getNormalizedIdentifier($object)
                        ));
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->admin->trans(
                            'flash_edit_success',
                            array('%name%' => $this->admin->toString($object)),
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($object);

                } catch (ModelManagerException $e) {
                    $this->logModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->admin->trans(
                            'flash_edit_error',
                            array('%name%' => $this->admin->toString($object)),
                            'SonataAdminBundle'
                        )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }
        $related = array();

        $em = $this->get('doctrine')->getManager();
        $products = $em->createQuery('SELECT p FROM LovochemieBundle:Product p LEFT JOIN p.productHasMedias phm LEFT JOIN p.productHasDocuments phd LEFT JOIN phd.download d WHERE phm.media = :file OR d.media = :file')->setParameter('file', $object)->getResult();

        $prod_admin = $this->get('lovochemie.admin.product');
        foreach ($products as $product) {
            $url = $prod_admin->generateObjectUrl('edit', $product);
            $related[] = array('url' => $url, 'object' => $product);
        }

        $downloads = $em->createQuery('SELECT d FROM LovochemieBundle:Download d WHERE d.media = :file')->setParameter('file', $object)->getResult();
        $down_admin = $this->get('lovochemie.admin.download');
        foreach ($downloads as $download) {
            $url = $down_admin->generateObjectUrl('edit', $download);
            $related[] = array('url' => $url, 'object' => $download);
        }

        $pages = $em->createQuery('SELECT p FROM SymbioOrangeGatePageBundle:Page p WHERE p.icon = :file')->setParameter('file', $object)->getResult();
        $page_admin = $this->get('sonata.page.admin.page');
        foreach ($pages as $page) {
            $url = $page_admin->generateObjectUrl('edit', $page);
            $related[] = array('url' => $url, 'object' => $page);
        }

        $blocks = $em->createQuery('SELECT b FROM SymbioOrangeGatePageBundle:Block b WHERE b.type = :type OR b.type = :type2')->setParameters(array('type' => 'lovochemie.block.service.homepage.grid.image', 'type2' => 'lovochemie.block.service.general.documents'))->getResult();
        foreach ($blocks as $block) {
                if ($block->getType() == 'lovochemie.block.service.homepage.grid.image') {
                    for ($i = 1; $i < 5; $i ++) {
                        if ($block->getSettings()['picture_'.$i] == $object->getId()) {
                            $url = $page_admin->generateObjectUrl('compose', $block->getPage());
                            $related[] = array('url' => $url, 'object' => $block->getPage());
                        }
                    }
                } else {
                    if (array_key_exists('mediaIds', $block->getSettings())) {
                        foreach (explode(',', $block->getSettings()['mediaIds']) as $media) {
                            try {
                                $down = $em->getRepository('LovochemieBundle:Download')->findOneById($media);
                            } catch(\Exception $e) {
                                $down = null;
                            }
                            if ($down && $down->getMedia() == $object) {
                                $url = $page_admin->generateObjectUrl('edit', $block->getPage());
                                $related[] = array('url' => $url, 'object' => $block->getPage());
                            }
                        }
                    } elseif (array_key_exists('downloadIds', $block->getSettings())) {
                        foreach (explode(',', $block->getSettings()['downloadIds']) as $media) {
                            try {
                                $down = $em->getRepository('LovochemieBundle:Download')->findOneById($media);
                            } catch(\Exception $e) {
                                $down = null;
                            }
                            if($down && $down->getMedia() == $object) {
                                $url = $page_admin->generateObjectUrl('compose', $block->getPage());
                                $related[] = array('url' => $url, 'object' => $block->getPage());
                            }
                        }
                    }
                }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'edit',
            'form'   => $view,
            'object' => $object,
            'related' => $related,
        ));
    }

        /**
     * Delete action
     *
     * @param int|string|null $id
     *
     * @return Response|RedirectResponse
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function deleteAction($id, Request $request = null)
    {
        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('DELETE', $object)) {
            throw new AccessDeniedException();
        }

        if ($this->getRestMethod() == 'DELETE') {
            // check the csrf token
            $this->validateCsrfToken('sonata.delete');

            try {
                $this->admin->delete($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'ok'));
                }

                $this->addFlash(
                    'sonata_flash_success',
                    $this->admin->trans(
                        'flash_delete_success',
                        array('%name%' => $this->admin->toString($object)),
                        'SonataAdminBundle'
                    )
                );

            } catch (ModelManagerException $e) {
                $this->logModelManagerException($e);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'error'));
                }

                $this->addFlash(
                    'sonata_flash_error',
                    $this->admin->trans(
                        'flash_delete_error',
                        array('%name%' => $this->admin->toString($object)),
                        'SonataAdminBundle'
                    )
                );
            }

            return $this->redirectTo($object);
        }
        $related = array();
        $em = $this->get('doctrine')->getManager();
        $products = $em->createQuery('SELECT p FROM LovochemieBundle:Product p LEFT JOIN p.productHasMedias phm LEFT JOIN p.productHasDocuments phd LEFT JOIN phd.download d WHERE phm.media = :file OR d.media = :file')->setParameter('file', $object)->getResult();
        $prod_admin = $this->get('lovochemie.admin.product');
        foreach ($products as $product) {
            $url = $prod_admin->generateObjectUrl('edit', $product);
            $related[] = array('url' => $url, 'object' => $product);
        }

        $downloads = $em->createQuery('SELECT d FROM LovochemieBundle:Download d WHERE d.media = :file')->setParameter('file', $object)->getResult();
        $down_admin = $this->get('lovochemie.admin.download');
        foreach ($downloads as $download) {
            $url = $down_admin->generateObjectUrl('edit', $download);
            $related[] = array('url' => $url, 'object' => $download);
        }


        $pages = $em->createQuery('SELECT p FROM SymbioOrangeGatePageBundle:Page p WHERE p.icon = :file')->setParameter('file', $object)->getResult();
        $page_admin = $this->get('sonata.page.admin.page');
        foreach ($pages as $page) {
            $url = $page_admin->generateObjectUrl('edit', $page);
            $related[] = array('url' => $url, 'object' => $page);
        }

        return $this->render($this->admin->getTemplate('delete'), array(
            'object'     => $object,
            'action'     => 'delete',
            'csrf_token' => $this->getCsrfToken('sonata.delete'),
            'related' => $related,
        ));
    }
}

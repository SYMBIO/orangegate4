<?php

namespace Symbio\OrangeGate\TranslationBundle\Admin;

use Symbio\OrangeGate\AdminBundle\Admin\Admin as BaseAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class LanguageTokenAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('token', 'text', array('label' => 'Key'))
            ->add('translations', 'sonata_type_collection', array(), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position'
            ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('token')
        ;
    }


    public function getExportFields()
    {
        $results = $this->getModelManager()->getExportFields($this->getClass());
        $results[] = 'export_translations';
        return $results;
    }


    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('token')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    // Fields to be shown on revisions
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('token', null, array('label' => 'Key'))
        ;
    }

    public function prePersist($object)
    {
        $em = $this->modelManager->getEntityManager('SymbioOrangeGateTranslationBundle:LanguageToken');

        $container = $this->getConfigurationPool()->getContainer();
        $cacheDir = $container->get('kernel')->getCacheDir();
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->in(array($cacheDir . "/../*/translations"))->files();

        foreach($finder as $file){
            unlink($file->getRealpath());
        }

        if (is_dir($cacheDir.'/translations')) {
            rmdir($cacheDir.'/translations');
        }

        foreach ($object->getTranslations() as $tr) {
            $tr->setLanguageToken($object);
        }
    }

    public function preUpdate($object)
    {
        $em = $this->modelManager->getEntityManager('SymbioOrangeGateTranslationBundle:LanguageToken');

        $container = $this->getConfigurationPool()->getContainer();
        $cacheDir = $container->get('kernel')->getCacheDir();
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->in(array($cacheDir . "/../*/translations"))->files();

        foreach($finder as $file){
            unlink($file->getRealpath());
        }
        
        if (is_dir($cacheDir.'/translations')) {
            rmdir($cacheDir.'/translations');
        }

        foreach ($object->getTranslations() as $tr) {
            $tr->setLanguageToken($object);
        }
    }

}
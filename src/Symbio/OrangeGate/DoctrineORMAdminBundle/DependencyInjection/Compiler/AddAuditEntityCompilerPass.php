<?php

namespace Symbio\OrangeGate\DoctrineORMAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class AddAuditEntityCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('simplethings_entityaudit.config')) {
            return;
        }

        $auditedEntities = $container->getParameter('simplethings.entityaudit.audited_entities');
        $force = $container->getParameter('sonata_doctrine_orm_admin.audit.force');

        foreach ($container->findTaggedServiceIds('sonata.admin') as $id => $attributes) {

            if ($attributes[0]['manager_type'] != 'orm') {
                continue;
            }

            if (true === $force && isset($attributes[0]['audit']) && false === $attributes[0]['audit']) {
                continue;
            }

            if (false === $force && (!isset($attributes[0]['audit']) || false === $attributes[0]['audit'])) {
                continue;
            }

            $definition = $container->getDefinition($id);
            $class = $this->getModelName($container, $definition->getArgument(1));
            $translationClass = $class.'Translation';
            if (class_exists($translationClass)) {
                $auditedEntities[] = $translationClass;
            }
        }

        $auditedEntities = array_unique($auditedEntities);

        $container->setParameter('simplethings.entityaudit.audited_entities', $auditedEntities);
        $container->getDefinition('sonata.admin.audit.manager')->addMethodCall('setReader', array('sonata.admin.audit.orm.reader', $auditedEntities));
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string                                                  $name
     *
     * @return string
     */
    private function getModelName(ContainerBuilder $container, $name)
    {
        if ($name[0] == '%') {
            return $container->getParameter(substr($name, 1, -1));
        }

        return $name;
    }
}

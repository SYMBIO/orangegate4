<?php
/**
 * Created by PhpStorm.
 * User: jiri.bazant
 * Date: 14.05.15
 * Time: 10:06
 */

namespace Symbio\OrangeGate\RevisionBundle\EventListener;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


class RelationAudit implements EventSubscriber {

    public function getSubscribedEvents()
    {
        return array(Events::prePersist, Events::postPersist);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->relationAudit($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->relationAudit($args);
    }

    // todo maybe some other events

    private function relationAudit($args) {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $reader = new AnnotationReader();
        $reflObj = new \ReflectionObject($entity);


        foreach ($reflObj->getProperties() as $reflProp) {
            if ($reader->getPropertyAnnotation($reflProp, 'Symbio\OrangeGate\RevisionBundle\Annotation\RelationAudit')) {
                $getterName = 'get' . ucfirst($reflProp->getName());
                if ($reflObj->hasMethod($getterName)) {
                    $relationEntity = $entity->$getterName();
                    if (is_a($relationEntity, 'ArrayCollection')) {
                        $relationEntity->map(array($this, 'forceRelationUpdate'));
                    }
                    else {
                        $this->forceRelationUpdate($relationEntity, $em);
                    }
                }
            }
        }
    }


    private function forceRelationUpdate($obj, $em) {
        if (method_exists($obj, 'setUpdatedAt')) {
            $obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
        }

    }

}
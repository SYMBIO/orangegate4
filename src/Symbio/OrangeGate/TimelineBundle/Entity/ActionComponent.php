<?php

namespace Symbio\OrangeGate\TimelineBundle\Entity;

use Sonata\TimelineBundle\Entity\ActionComponent as BaseActionComponent;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="timeline__action_component")
 */
class ActionComponent extends BaseActionComponent
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Action", inversedBy="actionComponents")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id", nullable=true)
     */
    protected $action;

    /**
     * @ORM\ManyToOne(targetEntity="Component")
     * @ORM\JoinColumn(name="component_id", referencedColumnName="id", nullable=true)
     */
    protected $component;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set action
     *
     * @param \Symbio\OrangeGate\TimelineBundle\Entity\Action $action
     * @return ActionComponent
     */
    public function setAction(ActionInterface $action = null)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return \Symbio\OrangeGate\TimelineBundle\Entity\Action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set component
     *
     * @param \Symbio\OrangeGate\TimelineBundle\Entity\Component $component
     * @return ActionComponent
     */
    public function setComponent(ComponentInterface $component)
    {
        $this->component = $component;

        return $this;
    }

    /**
     * Get component
     *
     * @return \Symbio\OrangeGate\TimelineBundle\Entity\Component
     */
    public function getComponent()
    {
        return $this->component;
    }
}

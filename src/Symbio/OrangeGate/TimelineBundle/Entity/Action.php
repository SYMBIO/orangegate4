<?php

namespace Symbio\OrangeGate\TimelineBundle\Entity;

use Sonata\TimelineBundle\Entity\Action as BaseAction;
use Spy\Timeline\Model\ActionComponentInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="timeline__action")
 */
class Action extends BaseAction
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ActionComponent", mappedBy="action", cascade={"persist"})
     */
    protected $actionComponents;

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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->actionComponents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actionComponents
     *
     * @param \Symbio\OrangeGate\TimelineBundle\Entity\ActionComponent $actionComponents
     * @return Action
     */
    public function addActionComponent(ActionComponentInterface $actionComponents)
    {
        $this->actionComponents[] = $actionComponents;

        return $this;
    }

    /**
     * Remove actionComponents
     *
     * @param \Symbio\OrangeGate\TimelineBundle\Entity\ActionComponent $actionComponents
     */
    public function removeActionComponent(\Symbio\OrangeGate\TimelineBundle\Entity\ActionComponent $actionComponents)
    {
        $this->actionComponents->removeElement($actionComponents);
    }

    /**
     * Get actionComponents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActionComponents()
    {
        return $this->actionComponents;
    }
}

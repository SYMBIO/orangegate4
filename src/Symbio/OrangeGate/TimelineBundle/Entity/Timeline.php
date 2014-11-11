<?php

namespace Symbio\OrangeGate\TimelineBundle\Entity;

use Sonata\TimelineBundle\Entity\Timeline as BaseTimeline;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Model\ComponentInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="timeline__timeline")
 */
class Timeline extends BaseTimeline
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Action")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id", nullable=true)
     */
    protected $action;

    /**
     * @ORM\ManyToOne(targetEntity="Component")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id", nullable=true)
     */
    protected $subject;

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
     * @return Timeline
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
     * Set subject
     *
     * @param \Symbio\OrangeGate\TimelineBundle\Entity\Component $subject
     * @return Timeline
     */
    public function setSubject(ComponentInterface $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return \Symbio\OrangeGate\TimelineBundle\Entity\Component
     */
    public function getSubject()
    {
        return $this->subject;
    }
}

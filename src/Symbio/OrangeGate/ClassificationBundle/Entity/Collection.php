<?php

namespace Symbio\OrangeGate\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCollection as BaseCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="classification__collection")
 */
class Collection extends BaseCollection
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Context", inversedBy="categories")
     * @ORM\JoinColumn(name="context_id", referencedColumnName="id", nullable=true)
     */
    protected $context;

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
     * Set context
     *
     * @param \Symbio\OrangeGate\ClassificationBundle\Entity\Context $context
     * @return Collection
     */
    public function setContext(\Sonata\ClassificationBundle\Model\ContextInterface $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return \Symbio\OrangeGate\ClassificationBundle\Entity\Context
     */
    public function getContext()
    {
        return $this->context;
    }
}

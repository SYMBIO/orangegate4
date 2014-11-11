<?php

namespace Symbio\OrangeGate\PageBundle\Entity;

use Sonata\PageBundle\Entity\BaseBlock as BaseBlock;
use Doctrine\ORM\Mapping as ORM;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\SiteInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="page__bloc")
 */
class Block extends BaseBlock
{
    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $children;

    /**
     * @var \Symbio\OrangeGate\PageBundle\Entity\Block
     */
    protected $parent;

    /**
     * @var \Symbio\OrangeGate\PageBundle\Entity\Page
     */
    protected $page;

    public function __construct()
    {
        parent::__construct();

        $this->setEnabled(true);
        $this->setPosition(1);
    }

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
     * Add children
     *
     * @param \Symbio\OrangeGate\PageBundle\Entity\Block $children
     * @return Block
     */
    public function addChild(BlockInterface $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Symbio\OrangeGate\PageBundle\Entity\Block $children
     */
    public function removeChild(BlockInterface $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Symbio\OrangeGate\PageBundle\Entity\Block $parent
     * @return Block
     */
    public function setParent(BlockInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Symbio\OrangeGate\PageBundle\Entity\Block
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set page
     *
     * @param \Symbio\OrangeGate\PageBundle\Entity\Page $page
     * @return Block
     */
    public function setPage(PageInterface $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Symbio\OrangeGate\PageBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }
}

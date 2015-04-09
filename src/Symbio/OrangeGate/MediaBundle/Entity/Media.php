<?php

namespace Symbio\OrangeGate\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="media__media")
 */
class Media extends BaseMedia
{

    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="lang", type="string", length=20, nullable=true)
     */
    protected $lang;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $galleryHasMedias;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToOne(targetEntity="Symbio\OrangeGate\ClassificationBundle\Entity\Category", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galleryHasMedias = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add galleryHasMedias
     *
     * @param \Symbio\OrangeGate\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     * @return Media
     */
    public function addGalleryHasMedia(\Symbio\OrangeGate\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias)
    {
        $this->galleryHasMedias[] = $galleryHasMedias;

        return $this;
    }

    /**
     * Remove galleryHasMedias
     *
     * @param \Symbio\OrangeGate\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias
     */
    public function removeGalleryHasMedia(\Symbio\OrangeGate\MediaBundle\Entity\GalleryHasMedia $galleryHasMedias)
    {
        $this->galleryHasMedias->removeElement($galleryHasMedias);
    }

    /**
     * Get galleryHasMedias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryHasMedias()
    {
        return $this->galleryHasMedias;
    }

    /**
     * Set lang
     *
     * @param string $lang
     * @return Media
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

}

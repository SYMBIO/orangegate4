<?php

namespace Symbio\OrangeGate\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseGalleryHasMedia as BaseGalleryHasMedia;
use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\MediaInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="media__gallery_media")
 */
class GalleryHasMedia extends BaseGalleryHasMedia
{

    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Symbio\OrangeGate\MediaBundle\Entity\Gallery
     */
    protected $gallery;

    /**
     * @var \Symbio\OrangeGate\MediaBundle\Entity\Media
     */
    protected $media;

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
     * Set gallery
     *
     * @param \Symbio\OrangeGate\MediaBundle\Entity\Gallery $gallery
     * @return GalleryHasMedia
     */
    public function setGallery(GalleryInterface $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \Symbio\OrangeGate\MediaBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set media
     *
     * @param \Symbio\OrangeGate\MediaBundle\Entity\Media $media
     * @return GalleryHasMedia
     */
    public function setMedia(MediaInterface $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Symbio\OrangeGate\MediaBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}

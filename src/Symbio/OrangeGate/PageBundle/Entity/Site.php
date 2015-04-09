<?php

namespace Symbio\OrangeGate\PageBundle\Entity;

use Sonata\PageBundle\Entity\BaseSite as BaseSite;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="page__site")
 */
class Site extends BaseSite
{

    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="LanguageVersion", mappedBy="site", cascade={"persist"})
     */
    protected $languageVersions;

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
        $this->languageVersions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add languageVersions
     *
     * @param LanguageVersion $languageVersions
     * @return Site
     */
    public function addLanguageVersion(LanguageVersion $languageVersions)
    {
        $this->languageVersions[] = $languageVersions;

        return $this;
    }

    /**
     * Remove languageVersions
     *
     * @param LanguageVersion $languageVersions
     */
    public function removeLanguageVersion(LanguageVersion $languageVersions)
    {
        $this->languageVersions->removeElement($languageVersions);
    }

    /**
     * Get languageVersions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguageVersions()
    {
        return $this->languageVersions;
    }

    public function getLanguageVersion($locale)
    {
        foreach ($this->languageVersions as $lv) {
            if ($lv->getLocale() === $locale) {
                return $lv;
            }
        }

        return null;
    }
}

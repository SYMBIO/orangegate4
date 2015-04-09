<?php

namespace Symbio\OrangeGate\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LanguageTranslation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Symbio\OrangeGate\TranslationBundle\Entity\LanguageTranslationRepository")
 */
class LanguageTranslation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="catalogue", type="string", length=255)
     */
    private $catalogue = "messages";

    /**
     * @var string
     *
     * @ORM\Column(name="translation", type="text", nullable=true)
     */
    private $translation;

    /**
     * @ORM\Column(name="language", type="string", length=20)
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="Symbio\OrangeGate\TranslationBundle\Entity\LanguageToken", inversedBy="translations", fetch="EAGER")
     */
    private $languageToken;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set catalogue
     *
     * @param string $catalogue
     * @return LanguageTranslation
     */
    public function setCatalogue($catalogue)
    {
        $this->catalogue = $catalogue;

        return $this;
    }

    /**
     * Get catalogue
     *
     * @return string
     */
    public function getCatalogue()
    {
        return $this->catalogue;
    }

    /**
     * Set translation
     *
     * @param string $translation
     * @return LanguageTranslation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return LanguageTranslation
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set languageToken
     *
     * @param \Symbio\OrangeGate\TranslationBundle\Entity\LanguageToken $languageToken
     * @return LanguageTranslation
     */
    public function setLanguageToken(\Symbio\OrangeGate\TranslationBundle\Entity\LanguageToken $languageToken = null)
    {
        $this->languageToken = $languageToken;

        return $this;
    }

    /**
     * Get languageToken
     *
     * @return \Symbio\OrangeGate\TranslationBundle\Entity\LanguageToken
     */
    public function getLanguageToken()
    {
        return $this->languageToken;
    }

    public function __toString()
    {
        return $this->language." - ".$this->translation;
    }
}

<?php

namespace Symbio\OrangeGate\TranslationBundle\Services;

use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Doctrine\ORM\EntityManager;

class DBLoader implements LoaderInterface{
    private $translationRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager){
        $this->translationRepository = $entityManager->getRepository("SymbioOrangeGateTranslationBundle:LanguageTranslation");
    }

    function load($resource, $locale, $domain = 'messages'){
        $translations = $this->translationRepository->getTranslations($locale, $domain);
        $catalogue = new MessageCatalogue($locale);

        foreach($translations as $translation){
            $catalogue->set($translation->getLanguageToken()->getToken(), $translation->getTranslation(), $domain);
        }

        $catalogue->addResource(new FileResource($resource));
        return $catalogue;
    }
}
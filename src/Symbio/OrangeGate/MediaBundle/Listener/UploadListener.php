<?php

namespace Symbio\OrangeGate\MediaBundle\Listener;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symbio\OrangeGate\MediaBundle\Entity\Media;

class UploadListener
{
    private $manager;

    private $categoryManager;

    private $pool;

    public function __construct(MediaManagerInterface $manager, Pool $pool, CategoryManagerInterface $categoryManager)
    {
        $this->manager = $manager;
        $this->pool = $pool;
        $this->categoryManager = $categoryManager;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $providerName = 'sonata.media.provider.image';
        $request = $event->getRequest();
        $context = $request->get('context');
        $categoryId = $request->get('category');

        $category = $this->categoryManager->find($categoryId);

        $file = $event->getFile();

        $media = new Media();
        $media->setProviderName($providerName);
        $media->setContext($context);
        $media->setCategory($category);
        $media->setBinaryContent($file);

        $provider = $this->pool->getProvider($providerName);
        $provider->transform($media);

        $this->manager->save($media);

        $response = $event->getResponse();
        $response['name'] = $media->getName();
        $response['size'] = $media->getSize();
        $response['url'] = $provider->generatePublicUrl($media, $provider->getFormatName($media, 'widget_thumb'));
        $response['id'] = $media->getId();

        @unlink($file->getPathname());
    }
}

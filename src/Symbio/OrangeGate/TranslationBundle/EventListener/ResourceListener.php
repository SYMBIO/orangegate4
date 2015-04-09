<?php

namespace Symbio\OrangeGate\TranslationBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ResourceListener
{
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

    public function onKernelRequest(GetResponseEvent $event)
    {
    	$this->container->get('translator')->addResource('db', null, $this->container->get('request')->getLocale(), 'messages');

    	$this->container->get('translator')->addResource('db', null, $this->container->get('request')->getLocale(), 'validators');
    }
}
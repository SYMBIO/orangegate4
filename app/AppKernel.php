<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        // Please read http://symfony.com/doc/2.0/book/installation.html#configuration-and-setup
        bcscale(3);

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            // Symfony Standard Edition Bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Symfony CMF Standard Edition Bundles
            //new Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            //new Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle(),
            //new Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            //new Symfony\Cmf\Bundle\SimpleCmsBundle\CmfSimpleCmsBundle(),
            //new Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle(),
            //new Symfony\Cmf\Bundle\CreateBundle\CmfCreateBundle(),
            //new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            //new Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle(),
            //new Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle(),

            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle(),

            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            // USER
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),

            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),

            // Sonata Standard Edition Bundles
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Sonata\PageBundle\SonataPageBundle(),
            new Symbio\OrangeGate\PageBundle\SymbioOrangeGatePageBundle(),
            new Sonata\NewsBundle\SonataNewsBundle(),
            new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),

            //new Sonata\MarkItUpBundle\SonataMarkItUpBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

            new Sonata\AdminBundle\SonataAdminBundle(),
            new Symbio\OrangeGate\AdminBundle\SymbioOrangeGateAdminBundle(),
            #new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            //new Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle(),
            new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),

            // SONATA CORE & HELPER BUNDLES
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            //new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),
            new Sonata\NotificationBundle\SonataNotificationBundle(),
            new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),

            // E-COMMERCE
            new Sonata\BasketBundle\SonataBasketBundle(),
            new Application\Sonata\BasketBundle\ApplicationSonataBasketBundle(),
            new Sonata\CustomerBundle\SonataCustomerBundle(),
            new Application\Sonata\CustomerBundle\ApplicationSonataCustomerBundle(),
            new Sonata\DeliveryBundle\SonataDeliveryBundle(),
            new Application\Sonata\DeliveryBundle\ApplicationSonataDeliveryBundle(),
            new Sonata\InvoiceBundle\SonataInvoiceBundle(),
            new Application\Sonata\InvoiceBundle\ApplicationSonataInvoiceBundle(),
            new Sonata\OrderBundle\SonataOrderBundle(),
            new Application\Sonata\OrderBundle\ApplicationSonataOrderBundle(),
            new Sonata\PaymentBundle\SonataPaymentBundle(),
            new Application\Sonata\PaymentBundle\ApplicationSonataPaymentBundle(),
            new Sonata\ProductBundle\SonataProductBundle(),
            new Application\Sonata\ProductBundle\ApplicationSonataProductBundle(),
            new Sonata\PriceBundle\SonataPriceBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new Sonata\CommentBundle\SonataCommentBundle(),
            new Application\Sonata\CommentBundle\ApplicationSonataCommentBundle(),

            // Search Integration
            //new FOS\ElasticaBundle\FOSElasticaBundle(),

            new Spy\TimelineBundle\SpyTimelineBundle(),
            new Sonata\TimelineBundle\SonataTimelineBundle(),
            new Application\Sonata\TimelineBundle\ApplicationSonataTimelineBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),

            new Sonata\Bundle\QABundle\SonataQABundle(),
            new Sonata\Bundle\DemoBundle\SonataDemoBundle(),
            new CBRE\DefaultBundle\CBREDefaultBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

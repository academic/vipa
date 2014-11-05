<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new OjstrMessage\MessageBundle\OjstrMessageMessageBundle(),
            new Ojstr\SearchBundle\OjstrSearchBundle(),
            new Ojs\AnalyticsBundle\OjsAnalyticsBundle(),
            new Ojstr\SiteBundle\OjstrSiteBundle(),
            new Ojstr\WorkflowBundle\OjstrWorkflowBundle(),
            new Ojstr\ManagerBundle\OjstrManagerBundle(),
            new Ojs\ApiBundle\OjsApiBundle(),
            new Ojstr\CliBundle\OjstrCliBundle(),
            new Ojstr\JournalBundle\OjstrJournalBundle(),
            new Ojstr\UserBundle\OjstrUserBundle(),
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
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

}

<?php

use Unifik\DatabaseConfigBundle\DependencyInjection\Compiler\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel {

    public function registerBundles() {
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
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new Ojs\SearchBundle\OjsSearchBundle(),
            new Ojs\AnalyticsBundle\OjsAnalyticsBundle(),
            new Ojs\SiteBundle\OjsSiteBundle(),
            new Ojs\WorkflowBundle\OjsWorkflowBundle(),
            new Ojs\ManagerBundle\OjsManagerBundle(),
            new Ojs\ApiBundle\OjsApiBundle(),
            new Ojs\CliBundle\OjsCliBundle(),
            new Ojs\JournalBundle\OjsJournalBundle(),
            new Ojs\UserBundle\OjsUserBundle(),
            new Ojs\OAIBundle\OjsOAIBundle(),
            new JMS\JobQueueBundle\JMSJobQueueBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new Ojs\ReportBundle\OjsReportBundle(),
            new Okulbilisim\FeedbackBundle\OkulbilisimFeedbackBundle(),
            new Okulbilisim\MessageBundle\OkulbilisimMessageBundle(),
            new Ojs\InstallerBundle\OjsInstallerBundle(),
            new Okulbilisim\CmsBundle\OkulbilisimCmsBundle(),
            new Unifik\DatabaseConfigBundle\UnifikDatabaseConfigBundle(),
            new Lsw\MemcacheBundle\LswMemcacheBundle(),
            new Noxlogic\RateLimitBundle\NoxlogicRateLimitBundle(),
            new Ojs\NotifierBundle\OjsNotifierBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new h4cc\AliceFixturesBundle\h4ccAliceFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    protected function getContainerBuilder() {
        return new ContainerBuilder(new ParameterBag($this->getKernelParameters()));
    }

}

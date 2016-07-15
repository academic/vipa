<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new Problematic\AclManagerBundle\ProblematicAclManagerBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new APY\DataGridBundle\APYDataGridBundle(),
            new SC\DatetimepickerBundle\SCDatetimepickerBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Prezent\Doctrine\TranslatableBundle\PrezentDoctrineTranslatableBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Norzechowicz\AceEditorBundle\NorzechowiczAceEditorBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Jb\Bundle\FileUploaderBundle\JbFileUploaderBundle(),
            new Tetranz\Select2EntityBundle\TetranzSelect2EntityBundle(),
            new Presta\SitemapBundle\PrestaSitemapBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Baskin\HistoryBundle\BaskinHistoryBundle(),
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            new BulutYazilim\FeedbackBundle\BulutYazilimFeedbackBundle(),
            new Ojs\CoreBundle\OjsCoreBundle(),
            new Ojs\SiteBundle\OjsSiteBundle(),
            new Ojs\AdminBundle\OjsAdminBundle(),
            new Ojs\ApiBundle\OjsApiBundle(),
            new Ojs\JournalBundle\OjsJournalBundle(),
            new Ojs\UserBundle\OjsUserBundle(),
            new Ojs\OAIBundle\OjsOAIBundle(),
            new BulutYazilim\LocationBundle\BulutYazilimLocationBundle(),
            // new Ojs\InstallerBundle\OjsInstallerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Ojs\AnalyticsBundle\OjsAnalyticsBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new Ojs\ImportBundle\ImportBundle(),
            new \OpenJournalSoftware\BibtexBundle\OpenJournalSoftwareBibtexBundle(),
            new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new h4cc\AliceFixturesBundle\h4ccAliceFixturesBundle();
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $thirdPartyDir = __DIR__.'/../thirdparty';
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        if ($fs->exists($thirdPartyDir)) {
            $finder = new \Symfony\Component\Finder\Finder();
            $finder->files()->in($thirdPartyDir);

            foreach ($finder as $file) {
                /** @var \Symfony\Component\Finder\SplFileInfo $file */
                $bundleConfig = json_decode(file_get_contents($file->getRealpath()), true);
                if ($bundleConfig) {
                    $class = $accessor->getValue($bundleConfig, '[extra][bundle-class]');
                    if ($class && class_exists($class)) {
                        $bundles[] = new $class();
                    }
                    $otherClasses = $accessor->getValue($bundleConfig, '[extra][other-bundle-classes]');
                    if(is_array($otherClasses)) {
                        foreach($otherClasses as $otherClass){
                            $otherClassInstance = new $otherClass();
                            if(!in_array($otherClassInstance, $bundles))
                                $bundles[] = $otherClassInstance;
                        }
                    }
                }
            }
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    protected function initializeContainer()
    {
        parent::initializeContainer();
        /**
         * look for detail
         * @link {https://github.com/FriendsOfSymfony/FOSMessageBundle/issues/255}
         * @link {http://stackoverflow.com/a/37720827/2438520}
         */
        if (PHP_SAPI == 'cli' && $this->getEnvironment() != 'test') {
            $this->getContainer()->enterScope('request');
            $this->getContainer()->set('request', new \Symfony\Component\HttpFoundation\Request(), 'request');
        }
    }
}

<?php

namespace Ojs\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Ojs\CoreBundle\Events\CoreEvents;
use Ojs\CoreBundle\Events\CoreEvent;

class ThirdPartyInstallCommand extends ContainerAwareCommand
{
    private $packageName;

    private $packageData = array(
        'workflow' => array(
            'name' => 'Workflow',
            'description' => 'Ojs Workflow Plugin',
            'repositories' => array(
                array(
                    'type' => 'vcs',
                    'url' => 'https://bitbucket.org/bulutyazilim/workflowbundle.git',
                ),
            ),
            'require' => array(
                "bulutyazilim/workflow-bundle" => "dev-master",
            ),
            'extra' => array(
                'bundle-class' => 'BulutYazilim\\WorkflowBundle\\WorkflowBundle',
            ),
        ),
        'endorsement' => array(
            'name' => 'Endorsement',
            'description' => 'Ojs Endorsement Plugin',
            'repositories' => array(
                array(
                    'type' => 'vcs',
                    'url' => 'https://bitbucket.org/bulutyazilim/endorsment.git',
                ),
            ),
            'require' => array(
                "bulutyazilim/endorsement-bundle" => "dev-master",
            ),
            'extra' => array(
                'bundle-class' => 'BulutYazilim\\EndorsementBundle\\EndorsementBundle',
            ),
        ),
        'doi' => array(
            'name' => 'Doi',
            'description' => 'Ojs Doi Plugin',
            'repositories' => array(
                array(
                    'type' => 'vcs',
                    'url' => 'https://bitbucket.org/bulutyazilim/ojsdoibundle.git',
                ),
            ),
            'require' => array(
                "bulutyazilim/ojs-doi-bundle" => "dev-master",
            ),
            'extra' => array(
                'bundle-class' => 'BulutYazilim\\OjsDoiBundle\\OjsDoiBundle',
                "other-bundle-classes" => array(
                    "OldSound\\RabbitMqBundle\\OldSoundRabbitMqBundle"
                )
            ),
        ),
        'advanced-citation' => array(
            'name' => 'AdvancedCitationBundle',
            'description' => 'OJS Advanced Citation Bundle',
            'repositories' => array(
                array(
                    'type' => 'vcs',
                    'url' => 'https://bitbucket.org/bulutyazilim/advancedcitationbundle.git',
                ),
            ),
            'require' => array(
                "bulutyazilim/advanced-citation-bundle" => "dev-master",
            ),
            'extra' => array(
                'bundle-class' => 'BulutYazilim\\AdvancedCitationBundle\\AdvancedCitationBundle',
            ),
        ),
        'market' => array(
            'name' => 'MarketBundle',
            'description' => 'Market bundle for OJS',
            'repositories' => array(
                array(
                    'type' => 'vcs',
                    'url' => 'https://bitbucket.org/bulutyazilim/marketbundle.git',
                ),
            ),
            'require' => array(
                "bulutyazilim/market-bundle" => "dev-master",
            ),
            'extra' => array(
                'bundle-class' => 'Ojs\\MarketBundle\\OjsMarketBundle',
            ),
        ),
        'dergipark' => array(
            'name' => 'OjsDergiparkBundle',
            'description' => 'Dergipark bundle for OJS',
            'repositories' => array(
                array(
                    'type' => 'vcs',
                    'url' => 'https://bitbucket.org/bulutyazilim/ojsdergiparkbundle.git',
                ),
            ),
            'require' => array(
                "ojs/dergipark-bundle" => "dev-master",
            ),
            'extra' => array(
                'bundle-class' => 'Ojs\\DergiparkBundle\\OjsDergiparkBundle',
            ),
        )
    );

    protected function configure()
    {
        $this
            ->setName('ojs:install:package')
            ->setDefinition(
                array(
                    new InputArgument('packageName', InputArgument::REQUIRED, 'Package Name'),
                )
            )
            ->setDescription('OJS Package Installation');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->getContainer()->get('event_dispatcher');
        $this->packageName = $input->getArgument('packageName');
        if(!array_key_exists($this->packageName, $this->packageData)){
            $output->writeln(
                '<error>Package not exists!</error>'
            );

            return null;
        }
        /** @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $output->writeln(
            '<info>'.
            $this->packageData[$this->packageName]['description'].
            '</info>'
        );

        $this->composerUpdate($output, $kernel);
        $this->schemaUpdate($output, $kernel);
        $this->asseticDump($output, $kernel);

        $event = new CoreEvent([
            'bundleName' => $this->packageName
        ]);
        $dispatcher->dispatch(CoreEvents::OJS_INSTALL_3PARTY, $event);
    }

    private function composerUpdate(OutputInterface $output, KernelInterface $kernel)
    {
        $fs = new Filesystem();
        $thirdPartyDir = $kernel->getRootDir().'/../thirdparty';
        $composerFile = $thirdPartyDir.'/'.
            str_replace(array('-', '/'), '_', $this->packageData[$this->packageName]['name']).'.json';
        if (!$fs->exists($composerFile)) {
            if (!$fs->exists($thirdPartyDir)) {
                $fs->mkdir($thirdPartyDir);
            }
            $fs->touch($composerFile);
        }

        file_put_contents(
            $composerFile,
            str_replace('    ', '  ', json_encode($this->packageData[$this->packageName], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))."\n"
        );

        $install = new Process('php composer.phar update', $kernel->getRootDir().'/..', null, null, 600);
        $install->setPty(true);
        try {
            $install->mustRun();
            $output->writeln($install->getOutput());
        } catch (ProcessFailedException $e) {
            $output->writeln($e->getMessage());
        }
        if ($install->isSuccessful()) {
            $output->writeln('<info>Packages succesfully installed</info>');
        } else {
            $output->writeln('<error>Packages installation failed</error>');
        }
    }

    private function schemaUpdate(OutputInterface $output, KernelInterface $kernel)
    {
        $consolePath = $kernel->getRootDir().'/console';
        $schemaProcess = new Process(
            'php '.$consolePath.' doctrine:schema:update --force',
            $kernel->getRootDir().'/..',
            null,
            null,
            600
        );
        $schemaProcess->setPty(true);

        try {
            $schemaProcess->mustRun();
            $output->writeln($schemaProcess->getOutput());
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
            $output->writeln($e->getMessage());
        }
        if ($schemaProcess->isSuccessful()) {
            $output->writeln('<info>Schema succesfully updated</info>');
        } else {
            $output->writeln('<error>Schema update failed</error>');
        }
    }

    private function asseticDump(OutputInterface $output, KernelInterface $kernel)
    {
        $consolePath = $kernel->getRootDir().'/console';
        $assetProcess = new Process(
            'php '.$consolePath.' assets:install --env=prod && php '.$consolePath.' assetic:dump --env=prod &&  php '.$consolePath.' cache:clear --env=prod',
            $kernel->getRootDir().'/..',
            null,
            null,
            600
        );
        $assetProcess->setPty(true);

        try {
            $assetProcess->mustRun();
            $output->writeln($assetProcess->getOutput());
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
            $output->writeln($e->getMessage());
        }
        if ($assetProcess->isSuccessful()) {
            $output->writeln('<info>Assets succesfully installed</info>');
        } else {
            $output->writeln('<error>Assets installation failed</error>');
        }
    }
}

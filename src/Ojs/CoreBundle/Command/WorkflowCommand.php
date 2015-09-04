<?php

namespace Ojs\CoreBundle\Command;

use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class WorkflowCommand extends ContainerAwareCommand
{
    private $workflowData = array(
        'vcs' => 'git@bitbucket.org:okulbilisim/workflowbundle.git',
        'package' => 'okulbilisim/workflow-bundle',
        'version' => 'dev-master',
        'bundlePath' => 'OkulBilisim\WorkflowBundle\WorkflowBundle',
        'routing' => array(
            'resource' => '@WorkflowBundle/Resources/config/routing.yml',
            'prefix' => '/'
        )
    );

    protected function configure()
    {
        $this
            ->setName('ojs:install:workflow')
            ->setDescription('Ojs workflow installation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var KernelInterface $kernel */
        $kernel = $this->getContainer()->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $output->writeln(
            '<info>'.
            'Workflow Installation'.
            '</info>'
        );
        $composerFile = $kernel->getRootDir().'/../composer.json';


        $this->composerUpdate($output, $composerFile);
        $this->kernelManipulate($output, $kernel);
        $this->routingSet($output, $kernel);
        $this->asseticDump($output, $kernel, $composerFile);

    }
    private function routingSet(OutputInterface $output, KernelInterface $kernel)
    {
        $routingFile = $kernel->getRootDir().'/config/routing.yml';
        $yaml = new Parser();
        try {
            $routingYml = $yaml->parse(file_get_contents($routingFile));
            if (in_array('workflow', $routingYml, true)) {
                $output->writeln(
                    '<info>'.
                    'Already defined in app/config/routing.yml'.
                    '</info>'
                );
            } else {
                $routingYml['workflow'] = array(
                    'resource' => $this->workflowData['routing']['resource'],
                    'prefix' =>   $this->workflowData['routing']['prefix']
                );
                $dumper = new Dumper();
                $dumper->setIndentation(2);
                $yaml = $dumper->dump($routingYml, 4);

                file_put_contents($routingFile, $yaml);
            }

        } catch (ParseException $e) {
            $output->writeln(
                '<error>'.
                "Unable to parse the routing YAML string: ".$e->getMessage().
                '</error>'
            );
        }
    }
    private function composerUpdate(OutputInterface $output, $composerFile) {
        $composer = json_decode(file_get_contents($composerFile), true);
        if (!array_key_exists('repositories', $composer)) {
            $composer['repositories'] = array();
        }

        $addRepository = true;
        foreach ($composer['repositories'] as $repository) {
            if ($repository['url'] === $this->workflowData['vcs']) {
                $addRepository = false;
                $output->writeln(
                    '<info>'.
                    'Already defined in composer repository list'.
                    '</info>'
                );
                break;
            }
        }
        if ($addRepository) {
            $composer['repositories'][] = array(
                'type' => 'vcs',
                'url' => $this->workflowData['vcs']
            );
        }
        if (!array_key_exists('require', $composer)) {
            $composer['require'] = array();
        }
        if (array_key_exists($this->workflowData['package'], $composer['require'])) {
            $output->writeln(
                '<info>'.
                'Already defined in composer package list'.
                '</info>'
            );
        }
        $composer['require'][$this->workflowData['package']] = $this->workflowData['version'];
        file_put_contents(
            $composerFile,
            str_replace('    ', '  ', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))."\n"
        );

        $install = new Process('php composer.phar update', dirname($composerFile), null, null, 600);
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
    private function kernelManipulate(OutputInterface $output, KernelInterface $kernel){
        $kernelManipulator = new KernelManipulator($kernel);
        try {
            $addBundle = $kernelManipulator->addBundle($this->workflowData['bundlePath']);
        } catch (\RuntimeException $e) {
            $addBundle = true;
            $output->writeln(
                '<info>'.
                'Already defined in AppKernel.php Bundle list'.
                '</info>'
            );
        }
        if (!$addBundle) {
            $output->writeln(
                '<error>'.
                'Cannot added to AppKernel.php Bundle list > new '.$this->workflowData['bundlePath'].'()'.
                '</error>'
            );
        }
    }
    private function asseticDump(OutputInterface $output, KernelInterface $kernel, $composerFile) {
        $yaml = new Parser();
        $asseticFile = $kernel->getRootDir().'/config/assetic.yml';
        try {
            $asseticYml = $yaml->parse(file_get_contents($asseticFile));
            if (in_array('WorkflowBundle', $asseticYml['assetic']['bundles'], true)) {
                $output->writeln(
                    '<info>'.
                    'Already defined in app/config/assetic.yml'.
                    '</info>'
                );
            } else {
                $asseticYml['assetic']['bundles'][] = 'WorkflowBundle';
                $dumper = new Dumper();
                $dumper->setIndentation(2);

                $yaml = $dumper->dump($asseticYml, 4);

                file_put_contents($asseticFile, $yaml);
            }

        } catch (ParseException $e) {
            $output->writeln(
                '<error>'.
                "Unable to parse the assetic YAML string: ".$e->getMessage().
                '</error>'
            );
        }
        $consolePath = $kernel->getRootDir().'/console';
        $assetProcess = new Process(
            'php '.$consolePath.' assets:install --env=prod && php '.$consolePath.' assetic:dump --env=prod &&  php '.$consolePath.' cache:clear --env=prod',
            dirname($composerFile),
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

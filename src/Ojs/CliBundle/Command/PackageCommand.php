<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use wdm\debian\control\StandardFile;
use wdm\debian\Packager;

class PackageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ojs:package')
            ->setDescription('Creates an installable package of OJS')
            ->addArgument('type', InputArgument::OPTIONAL,
                'Package type, DEB or YUM. Both will be generated if nothing is specified');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Packaging OJS...</info>');

        $type = $input->getArgument('type');
        if ($type == null || $type == 'deb' || $type == 'DEB') {

            $dependencies = [
                'php5', 'php5-mysql', 'php5-mongo',
                'php5-mcrypt', 'php5-memcached', 'php5-curl',
                'memcached', 'mongodb'
            ];

            $control = new StandardFile();
            $control
                ->setPackageName('ojs')
                ->setVersion('1.5')
                ->setDepends($dependencies)
                ->setInstalledSize(102400)
                ->setMaintainer('Utku Aydın', 'utku.aydin@okulbilisim.com')
                ->setProvides('ojs')
                ->setDescription('Open Journal Software');

            $source = $this->getContainer()->getParameter('kernel.root_dir') . '/..';

            $packager = new Packager();
            $packager->setControl($control);
            $packager->setOutputPath('/tmp/ojs');
            $packager->addMount($source, '/var/www/ojs');
            $packager->run();

            $command = $packager->build('ojs.deb');
            $output->writeln('<info>Creating DEB file...</info>');
            $process = new Process($command);
            $process->setWorkingDirectory('/tmp');
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                $output->writeln('<error>' . $process->getErrorOutput() . '</error>');
            } else {
                $output->writeln('<info>' . $process->getOutput() . '</info>');
                $output->writeln('<info>Done! You can find the "ojs.deb" in ' . $process->getWorkingDirectory() . '</info>');
            }
        }
    }

}
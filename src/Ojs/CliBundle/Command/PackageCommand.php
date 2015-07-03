<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use wdm\debian\control\StandardFile;
use wdm\debian\Packager;

class PackageCommand extends ContainerAwareCommand
{
    const OPERATION_DIRECTORY = '/tmp/ojs';
    const OUTPUT_DIRECTORY = '/tmp/ojs/output';
    const REPO_DIRECTORY = '/tmp/ojs/repository';
    const DEB_FILE_NAME = 'ojs.deb';

    protected function configure()
    {
        $this
            ->setName('ojs:package')
            ->setDescription('Creates an installable package of OJS')
            ->addArgument('type', InputArgument::REQUIRED, 'Package type: ZIP, DEB or YUM');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        if ($type == 'deb' || $type == 'DEB') {
            $output->writeln('<info>Creating a Debian package...</info>');
            $filesystem = new FileSystem();

            try {
                $filesystem->mkdir(self::REPO_DIRECTORY);
                $filesystem->mkdir(self::OUTPUT_DIRECTORY);
            } catch (IOExceptionInterface $error) {
                echo "An error occurred while creating your directory at " . $error->getPath();
            }

            $output->writeln('<info>Archiving master branch...</info>');
            $archiver = new Process('git archive master | tar -x -C ' . self::REPO_DIRECTORY);
            $archiver->run();

            if (!$archiver->isSuccessful()) {
                $output->writeln('<info>Could not archive master branch.</info>');
                $output->writeln('<error>' . $archiver->getErrorOutput() . '</error>');
                return;
            }

            $dependencies = [
                'php5', 'php5-mysql', 'php5-mongo',
                'php5-mcrypt', 'php5-memcached', 'php5-curl',
                'memcached', 'mongodb'
            ];

            $control = new StandardFile();
            $control
                ->setPackageName('ojs')
                ->setProvides('ojs')
                ->setVersion('1.5')
                ->setDepends($dependencies)
                ->setInstalledSize(10240)
                ->setDescription('Open Journal Software')
                ->setMaintainer('Utku Aydın', 'utku.aydin@okulbilisim.com');

            $packager = new Packager();
            $packager->setControl($control);
            $packager->setOutputPath(self::OUTPUT_DIRECTORY);
            $packager->addMount(self::REPO_DIRECTORY, '/var/www/ojs');
            $packager->run();
            $command = $packager->build(self::DEB_FILE_NAME);

            $output->writeln('<info>Creating a DEB file...</info>');
            $process = new Process($command);
            $process->setWorkingDirectory(self::OPERATION_DIRECTORY);
            $process->run();

            if (!$process->isSuccessful()) {
                $output->writeln('<info>Could not create a DEB file.</info>');
                $output->writeln('<error>' . $process->getErrorOutput() . '</error>');
            } else {
                $output->writeln('<info>' . $process->getOutput() . '</info>');
                $output->writeln('<info>Done!</info>');
                $output->writeln('<info>Package is at: ' . self::OPERATION_DIRECTORY . '/' . self::DEB_FILE_NAME);
            }
        }
    }

}
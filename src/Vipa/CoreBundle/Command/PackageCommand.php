<?php

namespace Vipa\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use wdm\debian\control\StandardFile;
use wdm\debian\Packager;

class PackageCommand extends ContainerAwareCommand
{
    const OPERATION_DIRECTORY = '/tmp/vipa/';
    const OUTPUT_DIRECTORY = '/tmp/vipa/output';
    const REPO_DIRECTORY = '/tmp/vipa/repository';
    const DEB_FILE_NAME = 'vipa.deb';
    const ZIP_FILE_NAME = 'vipa.zip';

    protected function configure()
    {
        $this
            ->setName('vipa:package')
            ->setDescription('Creates an installable package of OJS')
            ->addArgument('type', InputArgument::REQUIRED, 'Package type: ZIP, DEB or YUM');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');

        if ($type == 'zip' || $type == 'ZIP') {
            $output->writeln('<info>Creating a ZIP package...</info>');
            $output->writeln('<info>Archiving master branch...</info>');
            $archiver = new Process('git archive --format zip --output ' . self::OPERATION_DIRECTORY . self::ZIP_FILE_NAME . ' master ');
            $archiver->run();

            if (!$archiver->isSuccessful()) {
                $output->writeln('<info>Could not archive master branch.</info>');
                $output->writeln('<error>' . $archiver->getErrorOutput() . '</error>');
                return;
            } else {
                $output->writeln('<info>Done!</info>');
                $output->writeln('<info>The archive is at: ' . self::OPERATION_DIRECTORY . self::ZIP_FILE_NAME);
            }
        }

        if ($type == 'deb' || $type == 'DEB') {
            $output->writeln('<info>Creating a Debian package...</info>');
            $filesystem = new Filesystem();

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

            $output->writeln('<info>Installing dependencies...</info>');
            $composer = new Process('composer install');
            $composer->setWorkingDirectory(self::REPO_DIRECTORY);
            $composer->setTimeout(3600);
            $composer->run();

            $bower = new Process('bower install');
            $bower->setWorkingDirectory(self::REPO_DIRECTORY);
            $bower->setTimeout(3600);
            $bower->run();

            $dependencies = [
                'php5', 'php5-mysql', 'php5-mongo',
                'php5-mcrypt', 'php5-memcached', 'php5-curl',
                'memcached', 'mongodb'
            ];

            $control = new StandardFile();
            $control
                ->setPackageName('vipa')
                ->setProvides('vipa')
                ->setVersion('1.5')
                ->setDepends($dependencies)
                ->setInstalledSize(10240)
                ->setDescription('Open Journal Software')
                ->setMaintainer('Utku Aydın', 'utku.aydin@okulbilisim.com');

            $packager = new Packager();
            $packager->setControl($control);
            $packager->setOutputPath(self::OUTPUT_DIRECTORY);
            $packager->setPostInstallScript($this->getContainer()->get('kernel')->getRootDir() . '/../tools/debian/postinst');
            $packager->setPostRemoveScript($this->getContainer()->get('kernel')->getRootDir() . '/../tools/debian/postrm');
            $packager->addMount(self::REPO_DIRECTORY, '/opt/vipa');
            $packager->run();
            $command = $packager->build(self::DEB_FILE_NAME);

            $output->writeln('<info>Creating a DEB file...</info>');
            $process = new Process($command);
            $process->setWorkingDirectory(self::OPERATION_DIRECTORY);
            $process->setTimeout(3600);
            $process->run();

            if (!$process->isSuccessful()) {
                $output->writeln('<info>Could not create a DEB file.</info>');
                $output->writeln('<error>' . $process->getErrorOutput() . '</error>');
            } else {
                $output->writeln('<info>Done!</info>');
                $output->writeln('<info>The package is at: ' . self::OPERATION_DIRECTORY . self::DEB_FILE_NAME);
            }
        }
    }

}

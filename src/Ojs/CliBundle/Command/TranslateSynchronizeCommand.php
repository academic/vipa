<?php

namespace Ojs\CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Dumper\YamlFileDumper;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;

/**
 * Helps merge two or more translations
 *
 * @link https://gist.github.com/dbu/5883029
 */
class TranslateSynchronizeCommand extends ContainerAwareCommand
{

    /**
     * @var LoaderInterface
     */
    private $loader;

    private $path;

    protected function configure()
    {
        $this
            ->setName('ojs:trans:sync')
            ->setDefinition(
                array(
                    new InputArgument('master', InputArgument::REQUIRED, 'The master language'),
                    new InputArgument('slave', InputArgument::REQUIRED, 'The slave language'),
                )
            )
            ->setDescription('Helps synchronize two translations.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->loader = new YamlFileLoader();
        $this->path = 'app/Resources/translations/';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $masterLanguage = $input->getArgument('master');
        $slaveLanguage = $input->getArgument('slave');
        $masterLanguageFile = $this->path.'messages.'.$masterLanguage.'.yml';
        $slaveLanguageFile = $this->path.'messages.'.$slaveLanguage.'.yml';

        $catMasterFile = $this->loader->load($masterLanguageFile, $masterLanguage);
        $catSlaveFile = $this->loader->load($slaveLanguageFile, $slaveLanguage);

        foreach ($catMasterFile->all() as $key => $value) {
            if (!$catSlaveFile->has($key)) {
                $catSlaveFile->set($key, "TODO: $value");
            }
        }

        $output->writeln('Slave file can modify');
        $dumper = new YamlFileDumper();
        $dumper->dump($catSlaveFile, array('path' => $this->path));
        /*unlink created trash file*/
        if (file_exists($slaveLanguageFile.'~')) {
            unlink($slaveLanguageFile.'~');
        }
        $output->writeln($slaveLanguageFile.' --> <info>Slave file updated</info>');
    }
}

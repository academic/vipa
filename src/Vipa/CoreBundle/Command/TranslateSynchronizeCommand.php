<?php

namespace Vipa\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Dumper\YamlFileDumper;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Console\Input\InputOption;

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
            ->setName('vipa:trans:sync')
            ->setDefinition(
                array(
                    new InputArgument('master', InputArgument::REQUIRED, 'The master language'),
                    new InputArgument('slave', InputArgument::REQUIRED, 'The slave language'),
                )
            )
            ->addOption('file-name', 'f', InputOption::VALUE_OPTIONAL, 'Translation file name', 'messages')
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
        $fileName = $input->getOption('file-name');
        $masterLanguageFile = $this->path.$fileName.'.'.$masterLanguage.'.yml';
        $slaveLanguageFile = $this->path.$fileName.'.'.$slaveLanguage.'.yml';

        if(!file_exists($slaveLanguageFile)){
            $touch = touch($slaveLanguageFile);
            if($touch){
                $output->writeln($slaveLanguageFile.' --> <info>Slave file created</info>');
            }else{
                $output->writeln($slaveLanguageFile.' --> <fg=black;bg=red>Slave file can not created<fg=black;bg=red>');
            }
        }

        $catMasterFile = $this->loader->load($masterLanguageFile, $masterLanguage, $fileName);
        $catSlaveFile = $this->loader->load($slaveLanguageFile, $slaveLanguage, $fileName);

        foreach ($catMasterFile->all($fileName) as $key => $value) {
            if (!$catSlaveFile->has($key, $fileName)) {
                $catSlaveFile->set($key, "TODO: ".$value, $fileName);
            }
        }
        $messages = $catSlaveFile->all($fileName);
        ksort($messages);
        $catSlaveFile->replace($messages, $fileName);
        foreach($messages as $key => $value){
            $catSlaveFile->set($key, $value, $fileName);
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

<?php

namespace Ojs\CliBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Dumper\YamlFileDumper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

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
            ->setDefinition(array(
                new InputArgument('master', InputArgument::REQUIRED, 'The master language'),
                new InputArgument('slave', InputArgument::REQUIRED, 'The slave language'),
                new InputArgument('bundle', InputArgument::REQUIRED, 'The bundle names with commas or use all'),
                new InputOption('execute', null, InputOption::VALUE_NONE, 'Executes translation synchronize process')
            ))
            ->setDescription('Helps synchronize two translations.')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command helps synchronize two different translation.
For example, you created all translations like messages.en.yml command
will create slave translation file and key values.

You can create values that not exist in slave language translation according to master for all Bundles.
Command will show file will update:

<info>php %command.full_name% en tr all</info>

If you want to execute add --execute argument:

<info>php %command.full_name% --execute en tr all</info>

Bundle names must separate with comma, if you want synchronize all bundles use all argument:

<info>php %command.full_name% --execute en tr User,Site,Manager</info>

EOF
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->loader = new YamlFileLoader;
        $this->path = 'src/Ojs/';
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $masterLanguage = $input->getArgument('master');
        $slaveLanguage = $input->getArgument('slave');
        $bundles = $input->getArgument('bundle');
        $execute = $input->getOption('execute');

        if ($bundles == 'all') {
            foreach (glob($this->path . '*', GLOB_ONLYDIR) as $bundle_name) {
                $bundle_name = str_replace($this->path, '', $bundle_name);
                $bundle_array[] = $bundle_name;
            };
        } else {
            $bundle_array = explode(",", $input->getArgument('bundle'));
            foreach ($bundle_array as $key => $value) {
                $bundle_name = $value . 'Bundle';
                $bundle_array[$key] = $bundle_name;
            }
        }

        foreach ($bundle_array as $bundle_name) {

            $translationPath = $this->path . $bundle_name . '/Resources/translations';

            if (!is_dir($translationPath)) {
                continue;
            }
            $finder = Finder::create()->name('*.' . $masterLanguage . '.yml')->in($translationPath);

            $output->writeln("------------------------------------------------------------");
            $output->writeln($bundle_name.' -> Will Scan');
            foreach ($finder as $filename) {
                if (file_exists($filename)){
                    $output->writeln($filename.' --> Master Translation File Founded.');
                    $this->synchronize($filename->getRealpath(), $masterLanguage, $slaveLanguage, $translationPath, $execute, $output);
                }
            }
        }
    }

    protected function synchronize($filename, $master, $slave, $translationPath, $execute, OutputInterface $output)
    {
        $matches = array();
        preg_match('#^.*/(.*)\.' . $master . '\.yml$#', $filename, $matches);
        $domain = $matches[1];
        $slaveFile = preg_replace('#\.' . $master . '\.yml$#', '.' . $slave . '.yml', $filename);

        $catMasterFile = $this->loader->load($filename, $master, $domain);

        if (file_exists($slaveFile)) {
            $catSlaveFile = $this->loader->load($slaveFile, $slave, $domain);
            $output->writeln($slaveFile.' --> Slave file found');
        } else {
            $catSlaveFile = new MessageCatalogue($slave);
            $output->writeln('Slave file not found and created');
        }

        $modified = false;
        foreach ($catMasterFile->all($domain) as $key => $value) {
            if (!$catSlaveFile->has($key, $domain)) {
                $catSlaveFile->set($key, "TODO: $value", $domain);
                $modified = true;
            }
        }

        if ($modified) {
            $output->writeln('Slave file can modify');
            $dumper = new YamlFileDumper();
            if ($execute === true) {
                $dumper->dump($catSlaveFile, array('path' => $translationPath));
                /*unlink created trash file*/
                if(file_exists($slaveFile . '~'))
                    unlink($slaveFile . '~');
                $output->writeln($slaveFile.' --> <info>Slave file updated</info>');
            }else{
                $output->writeln($slaveFile.' --> <info>If you execute file will be updated</info>');
            }
        }
    }

}

<?php

namespace Ojs\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ApiDocsDumpCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $apiViews;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Application
     */
    private $application;

    protected function configure()
    {
        $this
            ->setName('ojs:api:docs:dump')
            ->setDescription('Dumps api view docs to specified.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io               = new SymfonyStyle($input, $output);
        $this->container        = $this->getContainer();
        $this->apiViews         = $this->container->get('ojs_api.twig.api_extension')->getApiViews();
        $this->kernel           = $this->container->get('kernel');
        $this->application      = new Application($this->kernel);
        $this->application->setAutoExit(false);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());

        foreach($this->apiViews as $apiView){
            $bufferOutput = new BufferedOutput();
            $this->application->run(new StringInput(sprintf('api:doc:dump --view=%s', $apiView)), $bufferOutput);
            $viewDump = $bufferOutput->fetch();
            $viewDumpFile = __DIR__.'/../Resources/doc/'.$apiView.'-api-doc.md';
            file_put_contents($viewDumpFile, $viewDump);
            $this->io->writeln(sprintf("%s -> view dumped to %s", $apiView, $viewDumpFile));
        }
    }
}

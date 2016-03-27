<?php

namespace Ojs\CoreBundle\Command;

use Ojs\CoreBundle\Console\Style\OjsStyle;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\Mapping\ClassMetadata;

class ValidateEntitiesCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var OjsStyle
     */
    private $os;

    /**
     * @var SymfonyStyle
     */
    private $io;

    protected function configure()
    {
        $this
            ->setName('ojs:validate:entities')
            ->setDescription('Validate Entities.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em   = $this->getContainer()->get('doctrine')->getManager();
        $this->os   = new OjsStyle($input, $output);
        $this->io   = $this->os->getIo();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());

        $this->validateEntities();
        $this->io->text('Validation Finished');
    }

    /**
     * @return bool|null
     */
    private function validateEntities()
    {
        $this->io->newLine();
        $this->io->text('Validation Starting for all entities');

        $metas = $this->em->getMetadataFactory()->getAllMetadata();
        /** @var ClassMetadata $meta */
        foreach ($metas as $meta) {
            if($meta->isMappedSuperclass){
                continue;
            };
            $reflClass = $meta->getReflectionClass();
            if($reflClass->hasMethod('__toString')){
                $this->io->text(sprintf('%s %s -> have __toString function', $this->os->okSign(),$meta->getName()));
            }else{
                $this->io->text(sprintf('%s %s -> have not __toString function', $this->os->warningSign(),$meta->getName()));
            }
        }
    }
}

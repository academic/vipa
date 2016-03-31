<?php

namespace Ojs\CoreBundle\Command;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Events\EventDetail;
use Ojs\CoreBundle\Events\MailEventsInterface;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\MailTemplate;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class MailEventsSynchronizeCommand
 * @package Ojs\CoreBundle\Command
 */
class JournalStatusSynchronizeCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Collection|Journal[]
     */
    private $allJournals;

    /**
     * @var array
     */
    private $journalStatuses = [];

    /**
     * @var boolean
     */
    private $published;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('ojs:journal:status:sync')
            ->addArgument('statuses', InputArgument::REQUIRED, 'Specify which status will be searched, comma seperated, must be string')
            ->addArgument('publishedStatus', InputArgument::REQUIRED, 'Specify which value you want to set for published field, must be boolean')
            ->setDescription('Synchronize journal status and published fields.')
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
        $this->em               = $this->container->get('doctrine')->getManager();
        $this->translator       = $this->container->get('translator');
        $this->allJournals      = $this->em->getRepository('OjsJournalBundle:Journal')->findAll();
        $statuses               = explode(',', $input->getArgument('statuses'));
        $publishedStatus        = $input->getArgument('publishedStatus');
        foreach($statuses as $status){
            $constValue = constant('Ojs\CoreBundle\Params\JournalStatuses::'.$status);
            $this->journalStatuses[] = $constValue;
        }
        if(!in_array($publishedStatus, ['1', '0', 'true', 'false'])){
            throw new \LogicException('published status value must be boolean');
        }
        $this->published        = (boolean)$input->getArgument('publishedStatus');
        if($publishedStatus == 'false'){
            $this->published = false;
        }
        $this->io->progressStart(count($this->allJournals));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getDescription());

        foreach($this->allJournals as $journal){
            if(in_array($journal->getStatus(), $this->journalStatuses)){
                $journal->setPublished($this->published);
            }
            $this->em->persist($journal);
            $this->io->progressAdvance();
        }
        $this->em->flush();
    }
}

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

/**
 * Class MailEventsSynchronizeCommand
 * @package Ojs\CoreBundle\Command
 */
class MailEventsSynchronizeCommand extends ContainerAwareCommand
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
     * @var Collection|Journal[]
     */
    private $allJournals;

    /**
     * @var array
     */
    private $langs;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('ojs:mail:events:sync')
            ->setDescription('Synchronize all mail events.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->container = $this->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
        $this->allJournals = $this->em->getRepository('OjsJournalBundle:Journal')->findAll();
        $this->langs = $this->container->getParameter('locale_support');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title('Synchronize all mail events.');
        $mailEventClassChain = $this->container->get('ojs_core.mail.event_chain')->getMailEvents();
        foreach($mailEventClassChain as $mailEventClass){
            if($mailEventClass instanceof MailEventsInterface){
                $getEvensOptions = $mailEventClass->getMailEventsOptions();
                foreach($getEvensOptions as $eventOption){
                    if($eventOption instanceof EventDetail){
                        $this->startMailEventSync($eventOption);
                    }else{
                        throw new \LogicException('all array item must be instance of EventDetail Class');
                    }
                }
            }
        }
    }

    /**
     * @param EventDetail $eventOption
     */
    private function startMailEventSync(EventDetail $eventOption)
    {
        $this->io->section(sprintf('Started event sync for -> %s -> %s', $eventOption->getName(), $eventOption->getGroup()));

        foreach($this->langs as $lang){
            if($eventOption->getGroup() == 'journal'){
                foreach($this->allJournals as $journal){
                    if(!$this->checkMailTemplateExists($eventOption, $lang, $journal)){
                        $this->createMailTemplateSkeleton($eventOption, $lang, $journal);
                    }
                }
            }else if($eventOption->getGroup() == 'admin'){
                if(!$this->checkMailTemplateExists($eventOption, $lang)){
                    $this->createMailTemplateSkeleton($eventOption, $lang);
                }
            }
        }
    }

    /**
     * @param EventDetail $eventOptions
     * @param string $lang
     * @param Journal|null $journal
     * @return null|object|MailTemplate
     */
    private function checkMailTemplateExists(EventDetail $eventOptions, $lang = 'en', Journal $journal = null)
    {
        return $this->em->getRepository('OjsJournalBundle:MailTemplate')->findOneBy([
            'type' => $eventOptions->getName(),
            'journal' => $journal,
            'lang' => $lang,
        ]);
    }

    /**
     * @param EventDetail $eventOptions
     * @param string $lang
     * @param Journal|null $journal
     */
    private function createMailTemplateSkeleton(EventDetail $eventOptions, $lang = 'en', Journal $journal = null)
    {
        $this->io->writeln(sprintf('Creating template for -> %s -> %s', $eventOptions->getName(), $journal == null ? 'admin': $journal->getTitle()));
        $mailTemplate = new MailTemplate();
        $mailTemplate
            ->setActive(true)
            ->setJournal($journal)
            ->setType($eventOptions->getName())
            ->setLang($lang)
            ->setTemplate('')
            ;
        $this->em->persist($mailTemplate);
        $this->em->flush();
    }
}

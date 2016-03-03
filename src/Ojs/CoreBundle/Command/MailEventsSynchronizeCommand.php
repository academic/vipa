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
    private $langs;

    /**
     * @var bool
     */
    private $syncDescriptions;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('ojs:mail:events:sync')
            ->addOption('sync-desc', null, InputOption::VALUE_NONE, 'Sync Mail Events Descriptions Too')
            ->setDescription('Synchronize all mail events.')
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
        $this->langs            = $this->container->getParameter('locale_support');
        $this->syncDescriptions = $input->getOption('sync-desc');
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
                if(!$this->checkMailTemplateExists($eventOption, $lang, null, true, false)){
                    $this->createMailTemplateSkeleton($eventOption, $lang, null, true, false);
                }
                foreach($this->allJournals as $journal){
                    if(!$this->checkMailTemplateExists($eventOption, $lang, $journal, false, true)){
                        $this->createMailTemplateSkeleton($eventOption, $lang, $journal, false, true, false);
                    }
                }
            }else if($eventOption->getGroup() == 'admin'){
                if(!$this->checkMailTemplateExists($eventOption, $lang, null, false, false)){
                    $this->createMailTemplateSkeleton($eventOption, $lang, null, false, false);
                }
            }
            if($this->syncDescriptions){
                $this->syncEventDescription($eventOption, $lang);
            }
            $this->em->flush();
        }
    }

    /**
     * @param EventDetail $eventOptions
     * @param string $lang
     * @param Journal|null $journal
     * @param bool $journalDefault
     * @param bool $useJournalDefault
     * @return null|object|MailTemplate
     */
    private function checkMailTemplateExists(EventDetail $eventOptions, $lang = 'en', Journal $journal = null, $journalDefault = false, $useJournalDefault = true)
    {
        return $this->em->getRepository('OjsJournalBundle:MailTemplate')->findOneBy([
            'type'              => $eventOptions->getName(),
            'journal'           => $journal,
            'lang'              => $lang,
            'journalDefault'    => $journalDefault,
            'useJournalDefault' => $useJournalDefault,
        ]);
    }

    /**
     * @param EventDetail $eventOptions
     * @param string $lang
     * @param Journal|null $journal
     * @param bool $journalDefault
     * @param bool $useJournalDefault
     * @param bool $active
     */
    private function createMailTemplateSkeleton(EventDetail $eventOptions, $lang = 'en', Journal $journal = null, $journalDefault = false, $useJournalDefault = true, $active = true)
    {
        $this->io->writeln(sprintf('Creating template for -> %s -> %s', $eventOptions->getName(), $journal == null ? 'admin': $journal->getTitle()));
        $mailTemplate = new MailTemplate();
        $mailTemplate
            ->setActive($active)
            ->setJournal($journal)
            ->setType($eventOptions->getName())
            ->setLang($lang)
            ->setTemplate('')
            ->setUseJournalDefault($useJournalDefault)
            ->setJournalDefault($journalDefault)
            ->setUpdatedBy('cli')
            ;
        $this->em->persist($mailTemplate);
    }

    private function syncEventDescription(EventDetail $eventDetail, $lang)
    {
        $findTemplates = $this->em->getRepository('OjsJournalBundle:MailTemplate')->findBy([
            'type' => $eventDetail->getName(),
            'lang' => $lang,
        ]);
        foreach($findTemplates as $template){
            $this->io->writeln(sprintf('Updating description for  -> %s', $eventDetail->getName()));
            $template->setDescription($this->translator->trans($eventDetail->getName(), [], null, $lang));
            $template->setUpdatedBy('cli');
            $this->em->persist($template);
        }
    }
}

<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\MailTemplate;
use Ojs\JournalBundle\Entity\SubmissionChecklist;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Parser;

class MailTemplateSamplesCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    private $em;

    protected function configure()
    {
        $this
            ->setName('ojs:mail:template:install:sample')
            ->setDescription('Ojs journal sample mail template install')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sample mail templates...');
        $allJournals = $this->getAllJournals();
        foreach($allJournals as $journal){
            $this->clearMailTemplates($journal, $output);
            $output->writeln('Creating for -> '. $journal->getTitle());
            $this->createJournalMailTemplates($journal, $output);
            $output->writeln('');
            $output->writeln('');
        }
    }

    private function getAllJournals()
    {
        return $this->em->getRepository('OjsJournalBundle:Journal')->findAll();
    }

    private function createJournalMailTemplates(Journal $journal, OutputInterface $output)
    {
        $container = $this->getContainer();
        $yamlParser = new Parser();
        $defaultTemplates = $yamlParser->parse(
            file_get_contents(
                $container->getParameter('kernel.root_dir').
                '/../src/Ojs/CoreBundle/Resources/data/mailtemplates.yml'
            )
        );
        foreach($defaultTemplates as $template){
            $newTemplate = new MailTemplate();
            $newTemplate
                ->setJournal($journal)
                ->setLang($template['lang'])
                ->setTemplate($template['template'])
                ->setSubject($template['subject'])
                ->setType($template['type'])
                ;
            $this->em->persist($newTemplate);
            $output->writeln('Mail Template persisted -> '. $template['subject']);
        }
        $this->em->flush();
    }

    /**
     * @param Journal $journal
     * @return bool
     */
    private function clearMailTemplates(Journal $journal, OutputInterface $output)
    {
        $mailTemplates = $this->em->getRepository('OjsJournalBundle:MailTemplate')->findBy([
            'journal' => $journal
        ]);
        if(count($mailTemplates)>0){
            foreach($mailTemplates as $mailTemplate){
                $mailTemplate->setUpdatedBy('cli');
                $this->em->persist($mailTemplate);
                $this->em->remove($mailTemplate);
            }
            $this->em->flush();
        }
        $output->writeln('All mail templates cleared');
        return true;
    }
}

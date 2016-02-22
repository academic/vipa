<?php

namespace Ojs\CoreBundle\Command;

use Ojs\JournalBundle\Entity\PeriodTranslation;
use Ojs\JournalBundle\Entity\PublisherTypesTranslation;
use Ojs\JournalBundle\Entity\SubjectTranslation;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class NormalizeTranslatableObjectsCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    private $em;

    /** @var SymfonyStyle */
    private $io;

    /** @var string */
    private $locale;

    protected function configure()
    {
        $this
            ->setName('ojs:normalize:translatable:objects')
            ->setDescription('Import normalize translatable objects.');
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
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->locale = $this->getContainer()->getParameter('locale');
        $this->io->title('Import normalize translatable objects.');

        $this->normalizeSubjects();
        $this->normalizePublisherTypes();
        $this->normalizePeriods();
    }

    private function normalizeSubjects()
    {
        $this->io->newLine();
        $this->io->text('normalizing subjects');
        $this->getContainer()->getParameter('locale');

        $this->io->progressStart();
        $subjects = $this->em->getRepository('OjsJournalBundle:Subject')->findAll();
        foreach($subjects as $subject){
            $getTranslation = $this->em->getRepository('OjsJournalBundle:SubjectTranslation')->findOneBy([
                'translatable' => $subject,
                'locale' => $this->locale
            ]);
            if(!$getTranslation){
                $this->io->progressAdvance();
                $newSubjectTranslation = new SubjectTranslation();
                $newSubjectTranslation->setTranslatable($subject);
                $newSubjectTranslation->setLocale($this->locale);
                $newSubjectTranslation->setSubject('-');
                $this->em->persist($newSubjectTranslation);
            }
        }
        $this->em->flush();
        $this->io->newLine();
    }

    private function normalizePublisherTypes()
    {
        $this->io->newLine();
        $this->io->text('normalizing publisher types');
        $this->getContainer()->getParameter('locale');

        $this->io->progressStart();
        $publisherTypes = $this->em->getRepository('OjsJournalBundle:PublisherTypes')->findAll();
        foreach($publisherTypes as $publisherType){
            $getTranslation = $this->em->getRepository('OjsJournalBundle:PublisherTypesTranslation')->findOneBy([
                'translatable' => $publisherType,
                'locale' => $this->locale
            ]);
            if(!$getTranslation){
                $this->io->progressAdvance();
                $newPublisherTypeTranslation = new PublisherTypesTranslation();
                $newPublisherTypeTranslation->setTranslatable($publisherType);
                $newPublisherTypeTranslation->setLocale($this->locale);
                $newPublisherTypeTranslation->setName('-');
                $this->em->persist($newPublisherTypeTranslation);
            }
        }
        $this->em->flush();
        $this->io->newLine();
    }

    private function normalizePeriods()
    {
        $this->io->newLine();
        $this->io->text('normalizing periods');
        $this->getContainer()->getParameter('locale');

        $this->io->progressStart();
        $periods = $this->em->getRepository('OjsJournalBundle:Period')->findAll();
        foreach($periods as $period){
            $getTranslation = $this->em->getRepository('OjsJournalBundle:PeriodTranslation')->findOneBy([
                'translatable' => $period,
                'locale' => $this->locale
            ]);
            if(!$getTranslation){
                $this->io->progressAdvance();
                $newPeriodTranslation = new PeriodTranslation();
                $newPeriodTranslation->setTranslatable($period);
                $newPeriodTranslation->setLocale($this->locale);
                $newPeriodTranslation->setPeriod('-');
                $this->em->persist($newPeriodTranslation);
            }
        }
        $this->em->flush();
        $this->io->newLine();
    }
}

<?php

namespace Ojs\CliBundle\Command;

use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Lang;
use Ojs\JournalBundle\Entity\Publisher;
use Ojs\JournalBundle\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SamplesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('ojs:install:samples')
            ->setDescription('Create some sample data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating sample data...');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $slug = $this->getContainer()->getParameter('defaultPublisherSlug');
        $publisher = new Publisher();
        $publisher->setCurrentLocale('en');
        $publisher->setName('OJS');
        $publisher->setSlug($slug);
        $publisher->setVerified(1);
        $publisher->setStatus(1);

        $em->persist($publisher);
        $em->flush();

        $subject1 = new Subject();
        $subject1->setCurrentLocale('en');
        $subject1->setSubject('Computer Science');

        $subject2 = new Subject();
        $subject2->setCurrentLocale('en');
        $subject2->setSubject('Journalism');

        $em->persist($subject1);
        $em->persist($subject2);
        $em->flush();

        $language1 = new Lang();
        $language1->setCurrentLocale('en');
        $language1->setName('English');
        $language1->setCode('en');
        $language1->setRtl(0);

        $language2 = new Lang();
        $language2->setCurrentLocale('tr');
        $language2->setName('Türkçe');
        $language2->setCode('tr');
        $language2->setRtl(0);

        $em->persist($language1);
        $em->persist($language2);
        $em->flush();

        $journal = new Journal();
        $journal->setCurrentLocale('en');
        $journal->setTitle('Introduction to OJS');
        $journal->setSubtitle('How to use OJS');
        $journal->setTitleAbbr('INTROJS');
        $journal->setUrl('http://ojs.io');
        $journal->setSlug('intro');
        $journal->addSubject($subject1);
        $journal->addSubject($subject2);
        $journal->setMandatoryLang($language2);

        $em->persist($journal);
        $em->flush();
    }
}

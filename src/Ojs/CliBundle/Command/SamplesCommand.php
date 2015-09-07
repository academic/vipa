<?php

namespace Ojs\CliBundle\Command;

use Ojs\AdminBundle\Entity\AdminAnnouncement;
use Ojs\AdminBundle\Entity\AdminPost;
use Ojs\JournalBundle\Entity\Article;
use Ojs\JournalBundle\Entity\ArticleTypes;
use Ojs\JournalBundle\Entity\Citation;
use Ojs\JournalBundle\Entity\ContactTypes;
use Ojs\JournalBundle\Entity\Issue;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\Section;
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

        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        $manipulator->create('sample_author', 'author', 'sample@ojs.io', false, false);

        $announcement = new AdminAnnouncement();
        $announcement->setTitle('We are online!');
        $announcement->setContent('We are now online and accepting submissions!');

        $em->persist($announcement);
        $em->flush();

        $post = new AdminPost();
        $post->setCurrentLocale('en');
        $post->setTitle('Welcome to OJS!');
        $post->setSlug('Welcome to OJS!');
        $post->setContent(
            'Hello! We are now online and waiting for your submissions. ' .
            'Our readers will be able to follow you and read your work ' .
            'right after it gets published!'
        );

        $em->persist($post);
        $em->flush();

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

        $articleTypes = [
            'Research', 'Analysis', 'Clinical Review',
            'Practice', 'Research Methods and Reporting',
            'Christmas Issue', 'Editorials', 'Blogs',
            'Case Reports', 'Letters (rapid responses)',
            'Obituaries', 'Personal Views', 'Fillers',
            'Minerva Pictures', 'Endgames',
            'What Your Patient is Thinking'
        ];

        foreach ($articleTypes as $typeName) {
            $type = new ArticleTypes();
            $type->setCurrentLocale('en');
            $type->setName($typeName);
            $em->persist($type);
        }

        $em->flush();

        $contactTypes = [
            'Journal Contact', 'Primary Contact',
            'Technical Contact', 'Author Support',
            'Subscription Support', 'Publisher Support',
            'Submission Support', 'Advertising', 'Media'
        ];

        foreach ($contactTypes as $typeName) {
            $type = new ContactTypes();
            $type->setCurrentLocale('en');
            $type->setName($typeName);
            $em->persist($type);
        }

        $em->flush();

        $journal = new Journal();
        $journal->setCurrentLocale('en');
        $journal->setPublisher($publisher);
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

        $issue = new Issue();
        $issue->setCurrentLocale('en');
        $issue->setJournal($journal);
        $issue->setTitle('First Issue: Hello OJS!');
        $issue->setNumber(1);
        $issue->setVolume(1);
        $issue->setYear(2015);
        $issue->setSpecial(1);
        $issue->setTags('fisrt, guide, tutorial');
        $issue->setDatePublished(new \DateTime('now'));

        $em->persist($issue);
        $em->flush();

        $section = new Section();
        $section->setCurrentLocale('en');
        $section->setJournal($journal);
        $section->setTitle('Tutorials');
        $section->setHideTitle(0);
        $section->setAllowIndex(1);

        $em->persist($section);
        $em->flush();

        $citation1 = new Citation();
        $citation1->setCurrentLocale('en');
        $citation1->setRaw('The Matrix [Motion picture]. (2001). Warner Bros. Pictures.');

        $em->persist($citation1);
        $em->flush();

        $article1 = new Article();
        $article1->setCurrentLocale('en');
        $article1->setJournal($journal);
        $article1->setSection($section);
        $article1->setIssue($issue);
        $article1->setTitle('Getting Started with OJS');
        $article1->setKeywords('ojs, intro, starting');
        $article1->setDoi('10.5281/zenodo.14791');
        $article1->setPubdate(new \DateTime('now'));
        $article1->setIsAnonymous(0);
        $article1->setFirstPage(1);
        $article1->setLastPage(5);
        $article1->setStatus(3);
        $article1->addCitation($citation1);

        $em->persist($article1);
        $em->flush();
    }
}

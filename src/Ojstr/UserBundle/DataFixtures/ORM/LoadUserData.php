<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Ojstr\UserBundle\Entity\User;

/**
 * Create sample user and role data
 */
class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     *
     * @var \Ojs\JournalBundle\Entity\Subject
     */
    private $subject;
    private $manager;
    private $encoder;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $role = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_USER'));
        $roleAuthor = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
        $roleEditor = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_EDITOR'));
        $roleJournalManager = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_JOURNAL_MANAGER'));
        $this->subject = $this->getReference('ref-subject');

        $author = $this->addAuthor();
        $author2 = $this->addAuthorAlt();
        $editor = $this->addEditor();
        $journalManager = $this->addJournalManager();

        $author->addRole($role);
        $author->addRole($roleAuthor);
        $author2->addRole($roleAuthor);
        $editor->addRole($role);
        $editor->addRole($roleEditor);
        $journalManager->addRole($role);
        $journalManager->addRole($roleJournalManager);

        $manager->persist($author);
        $manager->persist($author2);
        $manager->persist($editor);
        $manager->persist($journalManager);

        $manager->flush();

        $this->addReference('ref-author', $author);
        $this->addReference('ref-editor', $editor);
        $this->addReference('ref-journal-manager', $journalManager);
    }

    private function addAuthor()
    {
        $author = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($author);
        $author->setEmail("author@demo.com");
        $author->setIsActive(1);
        $password = $encoder->encodePassword("demo", $author->getSalt());
        $author->setPassword($password);
        $author->setStatus(1);
        $author->setUsername("demo_author");
        $author->setFirstName("Demo1");
        $author->setLastName("Author1");
        $author->addSubject($this->subject);
        $this->manager->persist($author);

        return $author;
    }

    private function addAuthorAlt()
    {
        $author2 = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($author2);
        $author2->setEmail("author2@demo.com");
        $author2->setIsActive(1);
        $author2->setPassword($encoder->encodePassword("demo", $author2->getSalt()));
        $author2->setStatus(1);
        $author2->setUsername("demo_author2");
        $author2->setFirstName("Demo2");
        $author2->setLastName("Author2");
        $author2->addSubject($this->subject);
        $this->manager->persist($author2);

        return $author2;
    }

    private function addEditor()
    {
        $editor = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($editor);
        $editor->setEmail("editor@demo.com");
        $editor->setIsActive(1);
        $passwordEditor = $encoder->encodePassword("demo", $editor->getSalt());
        $editor->setPassword($passwordEditor);
        $editor->setStatus(1);
        $editor->addSubject($this->subject);
        $editor->setUsername("demo_editor");
        $this->manager->persist($editor);

        return $editor;
    }

    private function addJournalManager()
    {
        $journalManager = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($journalManager);
        $journalManager->setEmail("journal.manager@demo.com");
        $journalManager->setIsActive(1);
        $passwordEditor = $encoder->encodePassword("demo", $journalManager->getSalt());
        $journalManager->setPassword($passwordEditor);
        $journalManager->setStatus(1);
        $journalManager->addSubject($this->subject);
        $journalManager->setUsername("demo_journal_manager");
        $this->manager->persist($journalManager);

        return $journalManager;
    }

    public function getOrder()
    {
        return 15;
    }

}

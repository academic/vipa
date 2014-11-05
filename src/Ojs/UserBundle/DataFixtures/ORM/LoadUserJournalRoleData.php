<?php

namespace Ojs\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Ojs\UserBundle\Entity\UserJournalRole;

/**
 * Create sample UserJournalRole entities
 */
class LoadUserJournalRoleData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // get first journal record
        $journal = $this->getReference('ref-journal');
        $journal2 = $this->getReference('ref-journal2');

        $author = $this->getReference('ref-author');
        $editor = $this->getReference('ref-editor');
        $journalManager = $this->getReference('ref-journal-manager');

        $roleSuperAdmin = $manager->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_SUPER_ADMIN'));
        $roleJournalManager = $manager->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_JOURNAL_MANAGER'));
        $roleAuthor = $manager->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
        $roleEditor = $manager->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_EDITOR'));
        $roleReviewer = $manager->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_REVIEWER'));

        $admins = $roleSuperAdmin->getUsers();

        // associate demo_author user as Author for a journal
        $ujr1 = new UserJournalRole();
        $ujr1->setUser($author);
        $ujr1->setRole($roleAuthor);
        $ujr1->setJournal($journal);
        $manager->persist($ujr1);

        // assign demo_editor user as Editor for a journal
        $ujr2 = new UserJournalRole();
        $ujr2->setUser($editor);
        $ujr2->setRole($roleEditor);
        $ujr2->setJournal($journal);
        $manager->persist($ujr2);
        $manager->flush();

        // assign demo_journal_manager user as Journal Manager for a journal
        $ujr_manager = new UserJournalRole();
        $ujr_manager->setUser($journalManager);
        $ujr_manager->setRole($roleJournalManager);
        $ujr_manager->setJournal($journal);
        $manager->persist($ujr_manager);
        $manager->flush();

        // add admin user as author

        $ujr3 = new UserJournalRole();
        $ujr3->setUser($admins[0]);
        $ujr3->setRole($roleAuthor);
        $ujr3->setJournal($journal);
        $manager->persist($ujr3);
        $manager->flush();

        // add admin user as editor

        $ujr4 = new UserJournalRole();
        $ujr4->setUser($admins[0]);
        $ujr4->setRole($roleEditor);
        $ujr4->setJournal($journal);
        $manager->persist($ujr4);
        $manager->flush();

        // add admin user as editor to journal2
        $ujr4_2 = new UserJournalRole();
        $ujr4_2->setUser($admins[0]);
        $ujr4_2->setRole($roleEditor);
        $ujr4_2->setJournal($journal2);
        $manager->persist($ujr4_2);
        $manager->flush();

        // add admin user as reviewer

        $ujr5 = new UserJournalRole();
        $ujr5->setUser($admins[0]);
        $ujr5->setRole($roleReviewer);
        $ujr5->setJournal($journal);
        $manager->persist($ujr5);
        $manager->flush();

        $this->addReference('ref-ujr-author', $ujr1);
    }

    public function getOrder()
    {
        return 16;
    }

}

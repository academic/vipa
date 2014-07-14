<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Ojstr\UserBundle\Entity\UserJournalRole;

/**
 * Create sample UserJournalRole entities
 */
class LoadUserJournalRoleData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        // get first journal record 
        $journal = $this->getReference('ref-journal');
        $journal2 = $this->getReference('ref-journal2');

        $author = $this->getReference('ref-author');
        $editor = $this->getReference('ref-editor');

        $roleSuperAdmin = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_SUPER_ADMIN'));
        $roleAuthor = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
        $roleEditor = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_EDITOR'));
        $roleReviewer = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_REVIEWER'));

        $admin = $manager->getRepository('OjstrUserBundle:UserJournalRole')->findOneBy(array('role_id' => $roleSuperAdmin->getId()));


        $ujr1 = new UserJournalRole();
        $ujr1->setUser($author);
        $ujr1->setRole($roleAuthor);
        $ujr1->setJournal($journal);
        $manager->persist($ujr1);

        $ujr2 = new UserJournalRole();
        $ujr2->setUser($editor);
        $ujr2->setRole($roleEditor);
        $ujr2->setJournal($journal);
        $manager->persist($ujr2);
        $manager->flush();

        // add admin user as author

        $ujr3 = new UserJournalRole();
        $ujr3->setUser($admin);
        $ujr3->setRole($roleAuthor);
        $ujr3->setJournal($journal);
        $manager->persist($ujr3);
        $manager->flush();

        // add admin user as editor

        $ujr4 = new UserJournalRole();
        $ujr4->setUser($admin);
        $ujr4->setRole($roleEditor);
        $ujr4->setJournal($journal);
        $manager->persist($ujr4);
        $manager->flush();

        // add admin user as editor to journal2
        $ujr4_2 = new UserJournalRole();
        $ujr4_2->setUser($admin);
        $ujr4_2->setRole($roleEditor);
        $ujr4_2->setJournal($journal2);
        $manager->persist($ujr4_2);
        $manager->flush();


        // add admin user as reviewer

        $ujr5 = new UserJournalRole();
        $ujr5->setUser($admin);
        $ujr5->setRole($roleReviewer);
        $ujr5->setJournal($journal);
        $manager->persist($ujr5);
        $manager->flush();

        $this->addReference('ref-ujr-author

        ', $ujr1);
    }

    public function getOrder() {
        return 16;
    }

}

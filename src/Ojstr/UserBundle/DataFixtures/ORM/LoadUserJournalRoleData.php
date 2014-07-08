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

        $author = $this->getReference('ref-author');
        $editor = $this->getReference('ref-editor');
        $admin = $manager->getRepository('OjstrUserBundle:User')->findByUsername('admin');

        $roleAuthor = $manager->getRepository('OjstrUserBundle:Role')->findByRole('ROLE_AUTHOR');
        $roleEditor = $manager->getRepository('OjstrUserBundle:Role')->findByRole('ROLE_EDITOR');
        $roleReviewer = $manager->getRepository('OjstrUserBundle:Role')->findByRole('ROLE_REVIEWER');


        $ujr1 = new UserJournalRole();
        $ujr1->setUser($author);
        $ujr1->setRole($roleAuthor[0]);
        $ujr1->setJournal($journal);
        $manager->persist($ujr1);

        $ujr2 = new UserJournalRole();
        $ujr2->setUser($editor);
        $ujr2->setRole($roleEditor[0]);
        $ujr2->setJournal($journal);
        $manager->persist($ujr2);
        $manager->flush();

        // add admin user as author

        $ujr3 = new UserJournalRole();
        $ujr3->setUser($admin[0]);
        $ujr3->setRole($roleAuthor[0]);
        $ujr3->setJournal($journal);
        $manager->persist($ujr3);
        $manager->flush();

        // add admin user as editor

        $ujr4 = new UserJournalRole();
        $ujr4->setUser($admin[0]);
        $ujr4->setRole($roleEditor[0]);
        $ujr4->setJournal($journal);
        $manager->persist($ujr4);
        $manager->flush();

        // add admin user as reviewer

        $ujr5 = new UserJournalRole();
        $ujr5->setUser($admin[0]);
        $ujr5->setRole($roleReviewer[0]);
        $ujr5->setJournal($journal);
        $manager->persist($ujr5);
        $manager->flush();

        $this->addReference('ref-ujr-author', $ujr1);
    }

    public function getOrder() {
        return 16;
    }

}

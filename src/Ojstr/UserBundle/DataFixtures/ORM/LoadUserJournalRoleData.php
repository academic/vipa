<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ojstr\UserBundle\Entity\UserJournalRole;

/**
 * Create sample UserJournalRole entities
 */
class LoadUserJournalRoleData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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
        $journal = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();

        $author = $manager->getRepository('OjstrUserBundle:User')->findByUsername('demo_author');
        $editor = $manager->getRepository('OjstrUserBundle:User')->findByUsername('demo_editor');

        $roleAuthor = $manager->getRepository('OjstrUserBundle:Role')->findByRole('ROLE_AUTHOR');
        $roleEditor = $manager->getRepository('OjstrUserBundle:Role')->findByRole('ROLE_AUTHOR');


        $ujr1 = new UserJournalRole();
        $ujr1->setUser($author[0]);
        $ujr1->setRole($roleAuthor[0]);
        $ujr1->setJournal($journal[0]);
        $manager->persist($ujr1);

        $ujr2 = new UserJournalRole();
        $ujr2->setUser($editor[0]);
        $ujr2->setRole($roleEditor[0]);
        $ujr2->setJournal($journal[0]);
        $manager->persist($ujr2);
        $manager->flush();
    }

    public function getOrder() {
        return 16;
    }

}

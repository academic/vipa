<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ojstr\UserBundle\Entity\User;
use Ojstr\UserBundle\Entity\UserJournalRole;

class LoadUserData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $author = new User();
        $editor = new User();
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($author);

        // get first journal record
        $journal = $manager->createQuery('SELECT c FROM OjstrJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();
        $roleAuthor = $manager->getRepository('OjstrJournalBundle:Journal')->findByRole('ROLE_AUTHOR');
        $roleEditor = $manager->getRepository('OjstrJournalBundle:Journal')->findByRole('ROLE_AUTHOR');


        $author->setEmail("author@demo.com");
        $author->setIsActive(1);
        $password = $encoder->encodePassword("demo", $author->getSalt());
        $author->setPassword($password);
        $author->setStatus(1);
        $author->setUsername("demo_author");
        $manager->persist($author);

        $editor->setEmail("author@demo.com");
        $editor->setIsActive(1);
        $passwordEditor = $encoder->encodePassword("demo", $author->getSalt());
        $editor->setPassword($passwordEditor);
        $editor->setStatus(1);
        $editor->setUsername("demo_editor");
        $manager->persist($editor);

        $manager->flush();

        // add user-journal-role 
        $ujr1 = new UserJournalRole();
        $ujr1->setUser($author);
        $ujr1->setRole($roleAuthor);
        $ujr1->setJournal($journal);
        $manager->persist($ujr1);

        $ujr2 = new UserJournalRole();
        $ujr2->setUser($author);
        $ujr2->setRole($roleAuthor);
        $ujr2->setJournal($journal);
        $manager->persist($ujr2);
        $manager->flush();
    }

    public function getOrder() {
        return 15;
    }

}

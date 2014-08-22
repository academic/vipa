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
class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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
        $author = new User();
        $author2 = new User();
        $editor = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($author);
        $role = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_USER'));
        $roleAuthor = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR'));
        $roleEditor = $manager->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_EDITOR'));

        $subject = $this->getReference('ref-subject');

        $author->setEmail("author@demo.com");
        $author->setIsActive(1);
        $password = $encoder->encodePassword("demo", $author->getSalt());
        $author->setPassword($password);
        $author->setStatus(1);
        $author->setUsername("demo_author");
        $author->setFirstName("Demo1");
        $author->setLastName("Author1");
        $author->addSubject($subject);
        $manager->persist($author);

        $author2->setEmail("author2@demo.com");
        $author2->setIsActive(1);
        $author2->setPassword($encoder->encodePassword("demo", $author->getSalt()));
        $author2->setStatus(1);
        $author2->setUsername("demo_author2");
        $author2->setFirstName("Demo2");
        $author2->setLastName("Author2");
        $author2->addSubject($subject);
        $manager->persist($author2);

        $editor->setEmail("author@demo.com");
        $editor->setIsActive(1);
        $passwordEditor = $encoder->encodePassword("demo", $author->getSalt());
        $editor->setPassword($passwordEditor);
        $editor->setStatus(1);
        $editor->addSubject($subject);
        $editor->setUsername("demo_editor");
        $manager->persist($editor);

        $author->addRole($role);
        $author->addRole($roleAuthor);
        $author2->addRole($roleAuthor);
        $editor->addRole($role);
        $editor->addRole($roleEditor);
        $manager->persist($author);
        $manager->persist($author2);
        $manager->persist($editor);
        $manager->flush();

        $this->addReference('ref-author', $author);
        $this->addReference('ref-editor', $editor);
    }

    public function getOrder() {
        return 15;
    }

}

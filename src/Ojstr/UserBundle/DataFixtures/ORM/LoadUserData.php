<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Ojstr\UserBundle\Entity\User;

/**
 * Create sample user and role data
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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
        $editor = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($author);
        $role = $manager->getRepository('OjstrUserBundle:Role')->findByRole('ROLE_USER');

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

        $author->addRole($role[0]);
        $manager->persist($editor);
        $editor->addRole($role[0]);
        $manager->persist($editor);
        $manager->flush();
    }

    public function getOrder() {
        return 15;
    }

}

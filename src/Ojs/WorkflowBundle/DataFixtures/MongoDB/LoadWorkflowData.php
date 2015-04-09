<?php

namespace Ojs\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Ojs\WorkflowBundle\Document\JournalWorkflowStep;
use Doctrine\Common\DataFixtures\AbstractFixture;

/**
 * @todo
 * Create sample workflow
 */
class LoadWorkflowData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {

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

    public function load(ObjectManager $dm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $journal = $em->getRepository("OjsJournalBundle:Journal")->find(1);
        if (!isset($journal)) {
            return;
        }
        $roleRepo = $em->getRepository('OjsUserBundle:Role');
        $roleEditor = $roleRepo->findOneByRole('ROLE_EDITOR');
        //$roleAuthor = $roleRepo->findOneByRole('ROLE_AUTHOR');
        $roleJournalManager = $roleRepo->findOneByRole('ROLE_JOURNAL_MANAGER');
        $serializer = $this->container->get('serializer');

        $step1 = new JournalWorkflowStep();
        $step1->setFirststep(true);
        $step1->setJournalid($journal->getId());
        $step1->setMaxdays(15);
        $step1->setStatus('First Review');
        $step1->setTitle('First Review');
        $step1->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));
        $dm->persist($step1);
        $dm->flush();

        $step2 = new JournalWorkflowStep();
        $step2->setLaststep(true);
        $step2->setJournalid($journal->getId());
        $step2->setMaxdays(15);
        $step2->setStatus('Publish');
        $step2->setTitle('Publish');
        $step2->addNextStep($step1);
        $step2->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));
        $dm->persist($step2);
        $step1->addNextStep($step2);
        $dm->persist($step1);
        $dm->flush();
    }

    public function getOrder()
    {
        return 22;
    }

}

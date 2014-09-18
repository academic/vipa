<?php

namespace Ojstr\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Ojstr\WorkflowBundle\Document\JournalWorkflowStep;
use Doctrine\Common\DataFixtures\AbstractFixture;

/**
 * @todo
 * Create sample workflow
 */
class LoadWorkflowData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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

    public function load(ObjectManager $dm)
    {
        $em = $this->container->get('doctrine')->getManager();
        $journal = $em->createQuery('SELECT c FROM OjstrJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();
        if (!isset($journal)) {
            return;
        }
        $step1 = new JournalWorkflowStep();
        $step1->setFirststep(true);
        $step1->setJournalid($journal[0]->getId());
        $step1->setMaxdays(15);
        $step1->setStatus('First Review');
        $step1->setTitle('First Review');
        $dm->persist($step1);
        $dm->flush();

        $step2 = new JournalWorkflowStep();
        $step2->setLaststep(true);
        $step2->setJournalid($journal[0]->getId());
        $step2->setMaxdays(15);
        $step2->setStatus('Publish');
        $step2->setTitle('Publish');
        $step2->setNextsteps(array(0 => array("id" => $step1->getId(), "title" => $step1->getTitle())));
        $dm->persist($step2);
        $step1->setNextsteps(array(0 => array("id" => $step2->getId(), "title" => $step2->getTitle())));
        $dm->persist($step1);
        $dm->flush();
    }

    public function getOrder()
    {
        return 20;
    }

}

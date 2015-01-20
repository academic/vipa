<?php

namespace Ojs\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Ojs\WorkflowBundle\Document\JournalWorkflowStepTemplate;
use Doctrine\Common\DataFixtures\AbstractFixture;

/**
 * @todo
 * Create sample workflow
 */
class LoadWorkflowTemplateData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $workflowController = new \Ojs\WorkflowBundle\Controller\WorkflowStepController();
        $step1 = new JournalWorkflowStepTemplate();
        $step1->setFirststep(true);
        $step1->setMaxdays(15);
        $step1->setStatus('First Review');
        $step1->setTitle('First Review');
        $dm->persist($step1);
        $dm->flush();


        $step2 = new JournalWorkflowStepTemplate();
        $step2->setLaststep(true);
        $step2->setMaxdays(15);
        $step2->setStatus('Author Edit');
        $step2->setTitle('Author Edit');
        $dm->persist($step2);

        $step3 = new JournalWorkflowStepTemplate();
        $step3->setLaststep(true);
        $step3->setMaxdays(15);
        $step3->setStatus('Publish');
        $step3->setTitle('Publish');
        $step3->setLaststep(true);
        $dm->persist($step3);
        $step3->setNextsteps($workflowController->prepareNextsteps(array($step1, $step2)));
        $step2->setNextsteps($workflowController->prepareNextsteps(array($step1, $step3)));
        $step1->setNextsteps($workflowController->prepareNextsteps(array($step2, $step3)));
        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);

        $dm->flush();
    }

    public function getOrder()
    {
        return 20;
    }

}

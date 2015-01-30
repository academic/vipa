<?php

namespace Ojs\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Ojs\WorkflowBundle\Document\JournalWorkflowTemplateStep;
use \Ojs\WorkflowBundle\Document\JournalWorkflowTemplate;
use Doctrine\Common\DataFixtures\AbstractFixture;

/**
 * @todo
 * Create workflow templates
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
        $em = $this->container->get('doctrine')->getManager();
        $journal = $em->createQuery('SELECT c FROM OjsJournalBundle:Journal c')
                        ->setMaxResults(1)->getResult();
        if (!isset($journal)) {
            return;
        }
        // load 3 base template
        $this->insertFirstTemplate($dm);
        $this->insertSecondTemplate($dm);
        $this->insertThirdTemplate($dm);
    }

    /**
     * 
     * @param mixed $dm doctrine mongodb manager
     */
    private function insertFirstTemplate($dm)
    {
        $step1 = new JournalWorkflowTemplateStep();
        $step1->setFirststep(true);
        $step1->setStatus('Editor is reviewing');
        $step1->setTitle('Editor Review');

        $step2 = new JournalWorkflowTemplateStep();
        $step2->setStatus('Author is updating');
        $step2->setTitle('Author Revision');

        $step3 = new JournalWorkflowTemplateStep();
        $step3->setLaststep(true);
        $step3->setStatus('Ready to publish');
        $step3->setTitle('Publish');

        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);
        $step1->setNextsteps(
                array(
                    array('id' => $step2->getId(), 'title' => $step2->gettitle()),
                    array('id' => $step3->getId(), 'title' => $step3->gettitle())
                )
        );
        $step2->setNextsteps(
                array(
                    array('id' => $step1->getId(), 'title' => $step1->gettitle())
                )
        );

        $step3->setNextsteps(
                array(
                    array('id' => $step1->getId(), 'title' => $step1->gettitle()),
                    array('id' => $step2->getId(), 'title' => $step2->gettitle())
                )
        );

        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);

        $dm->flush();

        $template1 = new JournalWorkflowTemplate();
        $template1->setTitle('One Step Review');
        $template1->setFirstNode($step1);

        $dm->persist($template1);
        $dm->flush();
        return $template1;
    }

    /**
     * @TODO
     * @param type $dm
     */
    private function insertSecondTemplate($dm)
    {
        
    }

    /**
     * @TODO
     * @param type $dm
     */
    private function insertThirdTemplate($dm)
    {
        
    }

    public function getOrder()
    {
        return 23;
    }

}

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

        $roleRepo = $this->container->get('doctrine')->getRepository('OjsUserBundle:Role');
        $roleEditor = $roleRepo->findOneByRole('ROLE_EDITOR');
        $roleAuthor = $roleRepo->findOneByRole('ROLE_AUTHOR');
        $roleJournalManager = $roleRepo->findOneByRole('ROLE_JOURNAL_MANAGER');
        $serializer = $this->container->get('serializer');

        $template = new JournalWorkflowTemplate();
        $template->setTitle('One Step Review');
        $template->setDescription('No reviewer needed.');
        $dm->persist($template);

        $step1 = new JournalWorkflowTemplateStep();
        $step1->setFirststep(true);
        $step1->setStatus('Editor is reviewing');
        $step1->setTitle('Editor Review');
        $step1->setCanReview(true);
        $step1->setCanEdit(true);
        $step1->setTemplate($template);
        $step1->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $step2 = new JournalWorkflowTemplateStep();
        $step2->setStatus('Author is updating');
        $step2->setTitle('Author Revision'); 
        $step2->setCanEdit(true);
        $step2->setTemplate($template);
        $step2->setRoles(array(
            json_decode($serializer->serialize($roleAuthor, 'json')),
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $step3 = new JournalWorkflowTemplateStep();
        $step3->setLaststep(true);
        $step3->setStatus('Ready to publish');
        $step3->setTitle('Ready to Publish'); 
        $step3->setCanReview(true);
        $step3->setCanEdit(true);
        $step3->setTemplate($template);
        $step3->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

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
        return $template;
    }

    /**
     * @TODO
     * @param type $dm
     */
    private function insertSecondTemplate($dm)
    {
        $roleRepo = $this->container->get('doctrine')->getRepository('OjsUserBundle:Role');
        $roleEditor = $roleRepo->findOneByRole('ROLE_EDITOR');
        $roleAuthor = $roleRepo->findOneByRole('ROLE_AUTHOR');
        $roleJournalManager = $roleRepo->findOneByRole('ROLE_JOURNAL_MANAGER');
        $roleReviewer = $roleRepo->findOneByRole('ROLE_REVIEWER');
        $serializer = $this->container->get('serializer');

        $template = new JournalWorkflowTemplate();
        $template->setTitle('Basic Academic Workflow');
        $template->setDescription('');
        $dm->persist($template);

        $step1 = new JournalWorkflowTemplateStep();
        $step1->setFirststep(true);
        $step1->setStatus('Editor is reviewing');
        $step1->setTitle('Editor Review'); 
        $step1->setCanReview(true);
        $step1->setCanEdit(true);
        $step1->setTemplate($template);
        $step1->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $step2 = new JournalWorkflowTemplateStep();
        $step2->setStatus('Author is updating');
        $step2->setTitle('Author Revision'); 
        $step2->setCanEdit(true);
        $step2->setOnlyreply(true);
        $step2->setTemplate($template);
        $step2->setRoles(array(
            json_decode($serializer->serialize($roleAuthor, 'json')),
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));


        $step3 = new JournalWorkflowTemplateStep();
        $step3->setOnlyreply(true);
        $step3->setStatus('In Review');
        $step3->setTitle('Review'); 
        $step3->setCanReview(true);
        $step3->setCanEdit(true);
        $step3->setTemplate($template);
        $step3->setRoles(array(
            json_decode($serializer->serialize($roleReviewer, 'json')),
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));


        $step4 = new JournalWorkflowTemplateStep();
        $step4->setLaststep(true);
        $step4->setStatus('Ready to Publish');
        $step4->setTitle('Ready to Publish'); 
        $step4->setCanReview(true);
        $step4->setCanEdit(true);
        $step4->setTemplate($template);
        $step4->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);
        $dm->persist($step4);

        $step1->setNextsteps(
                array(
                    array('id' => $step2->getId(), 'title' => $step2->gettitle()),
                    array('id' => $step3->getId(), 'title' => $step3->gettitle()),
                    array('id' => $step4->getId(), 'title' => $step4->gettitle())
                )
        );

        $step4->setNextsteps(
                array(
                    array('id' => $step1->getId(), 'title' => $step1->gettitle()),
                    array('id' => $step2->getId(), 'title' => $step2->gettitle())
                )
        );

        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);
        $dm->persist($step4);

        $dm->flush();
        return $template;
    }

    /**
     * @TODO
     * @param type $dm
     */
    private function insertThirdTemplate($dm)
    {
        $roleRepo = $this->container->get('doctrine')->getRepository('OjsUserBundle:Role');
        $roleEditor = $roleRepo->findOneByRole('ROLE_EDITOR');
        $roleAuthor = $roleRepo->findOneByRole('ROLE_AUTHOR');
        $roleJournalManager = $roleRepo->findOneByRole('ROLE_JOURNAL_MANAGER');
        $roleReviewer = $roleRepo->findOneByRole('ROLE_REVIEWER');
        $serializer = $this->container->get('serializer');

        $template = new JournalWorkflowTemplate();
        $template->setTitle('Extended Academic Workflow');
        $template->setDescription('Academic Workflow with extra steps. Contains "Redaction" and "Language Review" steps.');

        $dm->persist($template);

        $step1 = new JournalWorkflowTemplateStep();
        $step1->setFirststep(true);
        $step1->setStatus('Waiting to be accepted');
        $step1->setTitle('Secretary');
        $step1->setTemplate($template); 
        $step1->setCanRejectSubmission(true); 
        $step1->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $step2 = new JournalWorkflowTemplateStep();
        $step2->setStatus('Author is updating');
        $step2->setTitle('Author Revision');
        $step2->setOnlyreply(true); 
        $step2->setCanEdit(true);
        $step2->setTemplate($template);
        $step2->setRoles(array(
            json_decode($serializer->serialize($roleAuthor, 'json')),
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $step3 = new JournalWorkflowTemplateStep();
        $step3->setStatus('Editor is reviewing');
        $step3->setTitle('Editor Review');
        $step3->setCanReview(true);
        $step3->setCanEdit(true);
        $step3->setCanRejectSubmission(true);
        $step3->setTemplate($template);
        $step3->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $step4 = new JournalWorkflowTemplateStep();
        $step4->setStatus('Language Review');
        $step4->setTitle('Language Review');
        $step4->setCanReview(true);
        $step4->setCanEdit(true);
        $step4->setTemplate($template);
        $step4->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));


        $step5 = new JournalWorkflowTemplateStep();
        $step5->setOnlyreply(true);
        $step5->setStatus('In Review');
        $step5->setTitle('Review');
        $step5->setTemplate($template);
        $step5->setRoles(array(
            json_decode($serializer->serialize($roleReviewer, 'json')),
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));


        $step6 = new JournalWorkflowTemplateStep();
        $step6->setLaststep(true);
        $step6->setStatus('Ready to publish');
        $step6->setTitle('Publish');
        $step6->setRoles(array(
            json_decode($serializer->serialize($roleEditor, 'json')),
            json_decode($serializer->serialize($roleJournalManager, 'json'))
        ));

        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);
        $dm->persist($step4);
        $dm->persist($step5);
        $dm->persist($step6);


        $step1->setNextsteps(
                array( 
                    array('id' => $step3->getId(), 'title' => $step3->gettitle())
                )
        );

        $step3->setNextsteps(
                array(
                    array('id' => $step2->getId(), 'title' => $step2->gettitle()),
                     array('id' => $step4->getId(), 'title' => $step4->gettitle()),
                     array('id' => $step5->getId(), 'title' => $step5->gettitle()),
                    array('id' => $step6->getId(), 'title' => $step6->gettitle())
                )
        );

        $step4->setNextsteps(
                array(
                    array('id' => $step3->getId(), 'title' => $step3->gettitle())
                )
        );

        $step6->setNextsteps(
                array(
                    array('id' => $step3->getId(), 'title' => $step3->gettitle())
                )
        );

        $dm->persist($step1);
        $dm->persist($step2);
        $dm->persist($step3);
        $dm->persist($step4);
        $dm->persist($step5);
        $dm->persist($step6);

        $dm->flush();
        return $template;
    }

    public function getOrder()
    {
        return 23;
    }

}

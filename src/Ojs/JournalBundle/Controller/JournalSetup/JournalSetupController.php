<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\JournalSetupProgress;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Journal Setup Wizard controller.
 */
class JournalSetupController extends Controller
{
    /**
     * Admin can create new journal.
     * admin can resume from where he/she left.
     * @return mixed
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $journalCreatePermission = $this->isGranted('CREATE', new Journal());
        /** @var Journal $selectedJournal */
        $selectedJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        $selectedJournalEditPermission = $this->isGranted('EDIT', $selectedJournal);

        if (!$journalCreatePermission && !$selectedJournalEditPermission) {
            throw new AccessDeniedException();
        }
        if (!$selectedJournal && !$journalCreatePermission) {
            throw new NotFoundHttpException();
        }
        $journalSetup = new JournalSetupProgress();
        if($journalCreatePermission){
            /** @var JournalSetupProgress $journalSetup */
            $journalSetup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneByUser($user);
            if(!$journalSetup){
                $newJournal = new Journal();
                $newJournal->setTitle('');
                $newJournal->setTitleAbbr('');
                $newJournal->setSetupStatus(false);
                $em->persist($newJournal);

                $journalSetup->setUser($user);
                $journalSetup->setCurrentStep(1);
                $journalSetup->setJournal($newJournal);
                $em->persist($journalSetup);
                $em->flush();
            }
        }elseif(!$selectedJournal->getSetupStatus()){

            /** @var JournalSetupProgress $userSetup */
            $journalSetup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->findOneByJournal($selectedJournal);
        }elseif($selectedJournal->getSetupStatus()){

            $selectedJournal->setSetupStatus(false);
            $journalSetup->setUser($user);
            $journalSetup->setJournal($selectedJournal);
            $journalSetup->setCurrentStep(1);
            $em->persist($journalSetup);
            $em->flush();
        }
        return $this->redirect(
            $this->generateUrl(
                'ojs_journal_setup_resume',
                [
                    'setupId' => $journalSetup->getId(),
                ]
            ).'#'.
            $journalSetup->getCurrentStep()
        );
    }

    /**
     * if admin have not finished journal setup resumes from there.
     * @param $setupId
     * @return Response
     */
    public function resumeAction($setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());

        $stepsForms = array();
        //for 6 step create update forms
        foreach (range(1, 6) as $stepValue) {
            $stepsForms['step'.$stepValue] = $this->createFormView($journal, $stepValue);
        }
        $yamlParser = new Parser();
        $default_pages = $yamlParser->parse(
            file_get_contents(
                $this->container->getParameter('kernel.root_dir').
                '/../src/Ojs/JournalBundle/Resources/data/pagetemplates.yml'
            )
        );

        return $this->render(
            'OjsJournalBundle:JournalSetup:index.html.twig',
            array(
                'journal' => $journal,
                'steps' => $stepsForms,
                'default_pages' => $default_pages,
            )
        );
    }

    /**
     * @param $setup
     * @param $stepCount
     * @return FormView
     */
    private function createFormView($setup, $stepCount)
    {
        $stepClassName = 'Ojs\JournalBundle\Form\Type\JournalSetup\Step'.$stepCount;

        return $this->createForm(
            new $stepClassName(),
            $setup,
            array(
                'method' => 'POST',
            )
        )->createView();
    }
}

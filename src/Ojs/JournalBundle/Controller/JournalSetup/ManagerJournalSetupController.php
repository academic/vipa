<?php

namespace Ojs\JournalBundle\Controller\JournalSetup;

use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Journal Setup Wizard controller.
 */
class ManagerJournalSetupController extends Controller
{
    /**
     * Manager can edit current journal.
     * admin can resume from where he/she left.
     * @return mixed
     */
    public function indexAction()
    {
        $journalManager = $this->container->get('security.context')->isGranted('ROLE_JOURNAL_MANAGER');
        if(!$journalManager)
            throw new AccessDeniedException();
        $currentJournal = $this->get("ojs.journal_service")->getSelectedJournal();
        if($currentJournal){
            //for 6 step create update forms
            foreach (range(1, 6) as $stepValue) {
                $stepsForms['step' . $stepValue] = $this->createFormView($currentJournal, $stepValue);
            }
            $yamlParser = new Parser();
            $default_pages = $yamlParser->parse(file_get_contents(
                $this->container->getParameter('kernel.root_dir') .
                '/../src/Ojs/JournalBundle/Resources/data/pagetemplates.yml'
            ));
            return $this->render('OjsJournalBundle:JournalSetup:index.html.twig', array(
                'journal' => $currentJournal,
                'steps' => $stepsForms,
                'default_pages'=>$default_pages
            ));
        }
    }

    /**
     * @param $setup
     * @param $stepCount
     * @return \Symfony\Component\Form\FormView
     */
    public function createFormView($setup, $stepCount)
    {
        $stepClassName  = 'Ojs\JournalBundle\Form\JournalSetup\Step'.$stepCount;
        return $this->createForm(new $stepClassName(), $setup, array(
            'method' => 'POST',
        ))->createView();
    }
}
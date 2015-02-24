<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\JournalBundle\Document\JournalSuggestion;
use Ojs\JournalBundle\Document\InstituteSuggestion;
use Ojs\JournalBundle\Form\JournalSuggestionType;
use Ojs\JournalBundle\Form\InstituteSuggestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ojs\SiteBundle\Entity\Page;
use Ojs\SiteBundle\Form\PageType;

/**
 * Suggestion controller.
 *
 */
class SuggestionController extends Controller
{
    public function journalAction(Request $request)
    {
        $data = [];
        $suggestion = new JournalSuggestion();
        $form = $this->createForm(new JournalSuggestionType(), $suggestion, ['em' => $this->getDoctrine()->getManager()]);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $dm = $this->get('doctrine.odm.mongodb.document_manager');
                $suggestion->setUser($this->getUser()->getId());
                $suggestion->setCreatedAt(new \DateTime());
                $dm->persist($suggestion);
                $dm->flush();
                return $this->redirect($this->get('router')->generate('ojs_suggest_journal_success'));

            }
            $session = $this->get('session');
            $session->getFlashBag()
                ->add('error', $this->get('translator')->trans('An error has occured. Please check form and resubmit.'));
            $session->save();
        }
        $data['form'] = $form->createView();
        return $this->render('OjsSiteBundle:Suggestion:journal.html.twig', $data);
    }

    public function instituteAction(Request $request)
    {
        $data = [];
        $suggestion = new InstituteSuggestion();
        $form = $this->createForm(new InstituteSuggestionType(), $suggestion, [
            'em' => $this->getDoctrine()->getManager(),
            'helper'=>$this->get('okulbilisim_location.form.helper')
        ]);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $dm = $this->get('doctrine.odm.mongodb.document_manager');
                $suggestion->setUser($this->getUser()->getId());
                $suggestion->setCreatedAt(new \DateTime());
                $dm->persist($suggestion);
                $dm->flush();
                return $this->redirect($this->get('router')->generate('ojs_suggest_institute_success'));

            }
            $session = $this->get('session');
            $session->getFlashBag()
                ->add('error', $this->get('translator')->trans('An error has occured. Please check form and resubmit.'));
            $session->save();
        }
        $data['form'] = $form->createView();
        return $this->render('OjsSiteBundle:Suggestion:institute.html.twig', $data);
    }

    public function instituteSuccessAction()
    {
        return $this->render('OjsSiteBundle:Suggestion:institute_success.html.twig');
    }
    public function journalSuccessAction()
    {
        return $this->render('OjsSiteBundle:Suggestion:journal_success.html.twig');
    }
}

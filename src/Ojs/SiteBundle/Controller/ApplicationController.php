<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Document\InstitutionApplication;
use Ojs\JournalBundle\Document\JournalApplication;
use Ojs\JournalBundle\Form\InstitutionApplicationType;
use Ojs\JournalBundle\Form\JournalApplicationType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Application controller.
 *
 */
class ApplicationController extends Controller
{
    /**
     * @param  Request                                                                                       $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function journalAction(Request $request)
    {
        $data = [];
        $application = new JournalApplication();
        $form = $this->createForm(
            new JournalApplicationType(),
            $application,
            ['em' => $this->getDoctrine()->getManager()]
        );
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $dm = $this->get('doctrine.odm.mongodb.document_manager');
                $application->setStatus("0");
                $application->setUser($this->getUser()->getId());
                $application->setCreatedAt(new \DateTime());
                $dm->persist($application);
                $dm->flush();

                return $this->redirect($this->get('router')->generate('ojs_apply_journal_success'));
            }
            $session = $this->get('session');
            $session->getFlashBag()
                ->add(
                    'error',
                    $this->get('translator')->trans('An error has occured. Please check form and resubmit.')
                );
            $session->save();
        }
        $data['form'] = $form->createView();

        return $this->render('OjsSiteBundle:Application:journal.html.twig', $data);
    }

    public function institutionAction(Request $request)
    {
        $data = [];
        $application = new InstitutionApplication();
        $form = $this->createForm(
            new InstitutionApplicationType(),
            $application,
            [
                'em' => $this->getDoctrine()->getManager(),
                'helper' => $this->get('okulbilisim_location.form.helper'),
            ]
        );
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $dm = $this->get('doctrine.odm.mongodb.document_manager');
                $application->setStatus("0");
                $application->setUser($this->getUser()->getId());
                $application->setCreatedAt(new \DateTime());
                $dm->persist($application);
                $dm->flush();

                return $this->redirect($this->get('router')->generate('ojs_apply_institute_success'));
            }
            $session = $this->get('session');
            $session->getFlashBag()
                ->add(
                    'error',
                    $this->get('translator')->trans('An error has occured. Please check form and resubmit.')
                );
            $session->save();
        }
        $data['form'] = $form->createView();

        return $this->render('OjsSiteBundle:Application:institution.html.twig', $data);
    }

    public function instituteSuccessAction()
    {
        return $this->render('OjsSiteBundle:Application:institution_success.html.twig');
    }

    public function journalSuccessAction()
    {
        return $this->render('OjsSiteBundle:Application:journal_success.html.twig');
    }
}

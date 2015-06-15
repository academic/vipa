<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Document\JournalApplication;
use Ojs\JournalBundle\Entity\Institution;
use Ojs\JournalBundle\Form\Type\InstitutionApplicationType;
use Ojs\JournalBundle\Form\Type\JournalApplicationType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application controller.
 *
 */
class ApplicationController extends Controller
{
    /**
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function journalAction(Request $request)
    {
        $data = [];
        $application = new JournalApplication();
        $form = $this->createForm(
            new JournalApplicationType(),
            $application
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
        $em = $this->getDoctrine()->getManager();
        $application = new Institution();

        $form = $this->createForm(
            new InstitutionApplicationType(),
            $application,
            [
                'helper' => $this->get('ojs_location.form.helper'),
            ]
        );
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($application);
                $em->flush();

                return $this->redirect($this->get('router')->generate('ojs_apply_institute_success'));
            } else {
            }

            $session = $this->get('session');
            $session->getFlashBag()
                ->add(
                    'error',
                    $this->get('translator')->trans('An error has occured. Please check form and resubmit.')
                );
        }

        return $this->render('OjsSiteBundle:Application:institution.html.twig', array('form' => $form->createView()));
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

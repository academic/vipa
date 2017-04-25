<?php

namespace Vipa\SiteBundle\Controller;

use Vipa\AdminBundle\Form\Type\JournalApplicationType;
use Vipa\AdminBundle\Form\Type\PublisherApplicationType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\CoreBundle\Events\ResponseEvent;
use Vipa\CoreBundle\Events\TypeEvent;
use Vipa\CoreBundle\Params\JournalStatuses;
use Vipa\JournalBundle\Entity\ContactTypes;
use Vipa\JournalBundle\Entity\Journal;
use Vipa\JournalBundle\Entity\JournalApplicationUploadFile;
use Vipa\JournalBundle\Entity\JournalContact;
use Vipa\JournalBundle\Entity\Publisher;
use Vipa\JournalBundle\Event\Journal\JournalEvents;
use Vipa\JournalBundle\Form\Type\MinimalPublisherType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vipa\AdminBundle\Events\AdminEvent;
use Vipa\AdminBundle\Events\AdminEvents;

/**
 * Application controller.
 *
 */
class ApplicationController extends Controller
{
    /**
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function journalAction(Request $request)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $em = $this->getDoctrine()->getManager();
        $journalApplicationFiles = $em->getRepository('VipaJournalBundle:JournalApplicationFile')->findBy([
            'visible' => true,
            'locale' => $request->getLocale()
        ]);

        if (!$request->attributes->get('_system_setting')->isJournalApplicationActive()) {
            return $this->render(
                'VipaSiteBundle:Site:not_available.html.twig',
                [
                    'title' => 'title.journal_application',
                    'message' => 'message.application_not_available'
                ]
            );
        }

        $application = new Journal();

        $application = $this->setupJournalContacts($application);
        $defaultCountryId = $this->container->getParameter('country_id');
        $defaultCountry = $em->getRepository('BulutYazilimLocationBundle:Country')->find($defaultCountryId);
        $application->setCountry($defaultCountry);
        $application->setCurrentLocale($request->getDefaultLocale());

        foreach($journalApplicationFiles as $applicationFile){
            $uploadFileEntity = new JournalApplicationUploadFile();
            $application->addJournalApplicationUploadFile($uploadFileEntity);
        }

        $newPublisher = new Publisher();
        $newPublisher->setCurrentLocale($request->getDefaultLocale());

        $event = new TypeEvent(new JournalApplicationType());
        $dispatcher->dispatch(JournalEvents::INIT_APPLICATION_FORM, $event);

        $form = $this->createForm($event->getType(), $application);
        $publisherForm = $this->createForm(new MinimalPublisherType(), $newPublisher);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $publisherForm->handleRequest($request);

            $publisherFormIsOk = !$publisherForm->isSubmitted() || $publisherForm->isValid();

            if ($form->isValid() && $form->isSubmitted() && $publisherFormIsOk) {
                if ($publisherForm->isSubmitted()) {
                    $application->setPublisher($newPublisher);
                    $em->persist($newPublisher);
                }

                $application->setStatus(JournalStatuses::STATUS_APPLICATION);

                $application->getCurrentTranslation()->setLocale($application->getMandatoryLang()->getCode());
                /** @var JournalContact $contact */
                if($application->getJournalContacts()){
                    $journalDefaultContacts = $this->setupJournalContacts(new Journal());
                    foreach ($application->getJournalContacts() as $contactKey => $contact) {
                        $contact->setContactType($journalDefaultContacts->getJournalContacts()[$contactKey]->getContactType());
                        $contact->setJournal($application);
                        $em->persist($contact);
                    }
                }

                //setup journal application files
                /** @var JournalApplicationUploadFile $submissionFile */
                foreach ($application->getJournalApplicationUploadFiles() as $fileKey => $submissionFile) {
                    if(empty($submissionFile->getFile()) && $journalApplicationFiles[$fileKey]->getRequired()){
                        $this->errorFlashBag('please.install.required.files');
                        return $this->render('VipaSiteBundle:Application:journal.html.twig', [
                                'publisherForm' => $publisherForm->createView(),
                                'form' => $form->createView(),
                                'journalApplicationFiles' => $journalApplicationFiles
                            ]
                        );
                    }

                    $submissionFile
                        ->setTitle($journalApplicationFiles[$fileKey]->getTitle())
                        ->setDetail($journalApplicationFiles[$fileKey]->getDetail())
                        ->setLocale($journalApplicationFiles[$fileKey]->getLocale())
                        ->setRequired($journalApplicationFiles[$fileKey]->getRequired())
                        ->setJournal($application);

                    $em->persist($submissionFile);
                }

                $application->setSlug($application->getTitle());
                $em->persist($application);
                $em->flush();

                $event = new AdminEvent(['entity' => $application]);
                $dispatcher->dispatch(AdminEvents::JOURNAL_APPLICATION_HAPPEN, $event);
                return $this->redirectToRoute('vipa_apply_journal_success');
            }

            $this->errorFlashBag('An error has occured. Please check the form and resubmit.');
        }

        $event = new ResponseEvent('VipaSiteBundle:Application:journal.html.twig', [
            'form' => $form->createView(),
            'publisherForm' => $publisherForm->createView(),
            'journalApplicationFiles' => $journalApplicationFiles
        ]);
        $dispatcher->dispatch(AdminEvents::JOURNAL_APPLICATION_RESPONSE, $event);
        return $this->render($event->getTemplate(), $event->getData());
    }

    public function publisherAction(Request $request)
    {
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        if (!$request->attributes->get('_system_setting')->isPublisherApplicationActive()) {
            return $this->render(
                'VipaSiteBundle:Site:not_available.html.twig',
                [
                    'title' => 'title.journal_application',
                    'message' => 'message.application_not_available'
                ]
            );
        }

        $em = $this->getDoctrine()->getManager();
        $application = new Publisher();

        $form = $this->createForm(new PublisherApplicationType(), $application);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $application->setCurrentLocale($request->getDefaultLocale());
                $em->persist($application);
                $em->flush();

                $event = new AdminEvent([
                    'entity' => $application
                ]);
                $dispatcher->dispatch(AdminEvents::PUBLISHER_APPLICATION_HAPPEN, $event);
                return $this->redirectToRoute('vipa_apply_institute_success');
            }

            $this->errorFlashBag('An error has occured. Please check form and resubmit.');
        }

        return $this->render('VipaSiteBundle:Application:publisher.html.twig', array('form' => $form->createView()));
    }

    public function instituteSuccessAction()
    {
        return $this->render('VipaSiteBundle:Application:publisher_success.html.twig');
    }

    public function journalSuccessAction()
    {
        return $this->render('VipaSiteBundle:Application:journal_success.html.twig');
    }

    /**
     * @param Journal $journal
     * @return Journal
     */
    private function setupJournalContacts(Journal $journal)
    {
        $contactTypeNames = ['Editor', 'Technical Contact', 'Co-Editor'];
        $em = $this->getDoctrine()->getManager();
        $contactTypes = [];
        foreach($contactTypeNames as $contactTypeName){
            $contactTypeTranslation = $em->getRepository('VipaJournalBundle:ContactTypesTranslation')->findOneBy([
                'name' => $contactTypeName
            ]);
            $this->throw404IfNotFound($contactTypeTranslation, 'Not found '.$contactTypeName.' type contact type. please create');
            $contactTypes[] = $contactTypeTranslation->getTranslatable();
        }
        /** @var ContactTypes $contactType */
        foreach($contactTypes as $contactType){
            if(!is_null($contactType)){
                $journalContact = new JournalContact();
                $journalContact->setContactType($contactType);
                $journal->addJournalContact($journalContact);
            }
        }
        return $journal;
    }
}

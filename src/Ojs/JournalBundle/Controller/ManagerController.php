<?php

namespace Ojs\JournalBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\AdminBundle\Form\Type\JournalType;
use Ojs\AdminBundle\Form\Type\QuickSwitchType;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\JournalBundle\Entity\JournalSetting;
use Ojs\JournalBundle\Event\WorkflowEvent;
use Ojs\JournalBundle\Event\WorkflowEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Yaml\Parser;

class ManagerController extends Controller
{
    /**
     * @param  null     $journalId
     * @return Response
     */
    public function journalSettingsAction($journalId = null)
    {
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $em = $this->getDoctrine()->getManager();
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException($this->get('translator')->trans("You can't view this page."));
        }

        $form = $this->createJournalEditForm($journal);

        return $this->render(
            'OjsJournalBundle:Manager:journal_settings.html.twig',
            array(
                'entity' => $journal,
                'form' => $form->createView(),
            )
        );
    }

    private function createJournalEditForm(Journal $journal)
    {
        return $this->createForm(
            new JournalType(),
            $journal,
            array(
                'action' => $this->generateUrl('ojs_journal_settings_update', ['journalId' => $journal->getId()]),
                'method' => 'PUT',
            )
        );
    }

    /**
     * @param  Request                   $request
     * @return RedirectResponse|Response
     */
    public function updateJournalAction(Request $request)
    {
        /** @var Journal $entity */
        $em = $this->getDoctrine()->getManager();
        $entity = $this->get('ojs.journal_service')->getSelectedJournal();

        if (!$this->isGranted('EDIT', $entity)) {
            throw new AccessDeniedException("You not authorized for edit this journal!");
        }

        $this->throw404IfNotFound($entity);
        $editForm = $this->createJournalEditForm($entity);
        $editForm->submit($request);
        if ($editForm->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->successFlashBag('successful.update');

            return $this->redirectToRoute('ojs_journal_settings_index', ['journalId' => $entity->getId()]);
        }

        return $this->redirectToRoute('ojs_journal_settings_index', ['journalId' => $entity->getId()]);
    }

    /**
     * @todo setttings enumeration should be done, otherwise setting keys will be a garbage
     * @param  Request  $request
     * @param  integer  $journalId
     * @return Response
     */
    public function journalSettingsSubmissionAction(Request $request, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $journal  Journal */
        $journal = !$journalId ?
            $this->get("ojs.journal_service")->getSelectedJournal() :
            $em->getRepository('OjsJournalBundle:Journal')->find($journalId);

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException($this->get('translator')->trans("You can't view this page."));
        }

        if ($request->getMethod() == 'POST') {
            $submissionMandatoryLanguages = $request->get('submissionMandatoryLanguages');
            if (!empty($submissionMandatoryLanguages)) {
                $this->updateJournalSetting(
                    $journal,
                    'submissionMandatoryLanguages',
                    $submissionMandatoryLanguages,
                    true
                );
            }
            $submissionAbstractTemplate = $request->get('submissionAbstractTemplate');
            if (!empty($submissionAbstractTemplate)) {
                $this->updateJournalSetting(
                    $journal,
                    'submissionAbstractTemplate',
                    $submissionAbstractTemplate
                );
            }
            $copyrightStatement = $request->get('copyrightStatement');
            if (!empty($copyrightStatement)) {
                $this->updateJournalSetting(
                    $journal,
                    'copyrightStatement',
                    $copyrightStatement
                );
            }
        }
        $yamlParser = new Parser();
        $root = $this->container->getParameter('kernel.root_dir');
        $data = array(
            'settings' => array(
                'submissionMandatoryLanguages' => $journal->getSetting('submissionMandatoryLanguages') ?
                    json_decode($journal->getSetting('submissionMandatoryLanguages')->getValue()) :
                    null,
                'submissionAbstractTemplate' => $journal->getSetting('submissionAbstractTemplate') ?
                    $journal->getSetting('submissionAbstractTemplate')->getValue() :
                    null,
                'copyrightStatement' => $journal->getSetting('copyrightStatement') ?
                    $journal->getSetting('copyrightStatement')->getValue() :
                    null,
            ),
            'abstractTemplates' => $yamlParser->parse(
                file_get_contents(
                    $root.
                    '/../src/Ojs/JournalBundle/Resources/data/abstracttemplates.yml'
                )
            ),
            'copyrightTemplates' => $yamlParser->parse(
                file_get_contents(
                    $root.
                    '/../src/Ojs/JournalBundle/Resources/data/copyrightTemplates.yml'
                )
            ),
            'journal' => $journal,
            'allLanguages' => $journal->getLanguages(),
        );

        return $this->render('OjsJournalBundle:Manager:journal_settings_submission.html.twig', $data);
    }

    /**
     * @param  Journal            $journal
     * @param  string             $settingName
     * @param  string             $settingValue if null, function will return current value
     * @param  bool               $encoded      set true if setting stored as json_encoded
     * @return array|mixed|string
     */
    private function updateJournalSetting($journal, $settingName, $settingValue, $encoded = false)
    {
        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException($this->get('translator')->trans("You can't view this page."));
        }

        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetting $setting */
        $setting = $em->
        getRepository('OjsJournalBundle:JournalSetting')->
        findOneBy(array('journal' => $journal, 'setting' => $settingName));

        $settingString = $encoded ? json_encode($settingValue) : $settingValue;
        if ($setting) {
            $setting->setValue($settingString);
        } else {
            $setting = new JournalSetting($settingName, $settingString, $journal);
        }
        $em->persist($setting);
        $em->flush();

        return $setting ? ($encoded ? json_decode($setting->getValue()) : $setting->getValue()) : [];
    }

    /**
     * @param  Request      $req
     * @param  null|integer $journalId
     * @return Response
     */
    public function journalSettingsMailAction(Request $req, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $journal Journal */
        $journal = !$journalId ?
            $this->get("ojs.journal_service")->getSelectedJournal() :
            $em->getRepository('OjsJournalBundle:Journal')->find($journalId);

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException($this->get('translator')->trans("You can't view this page."));
        }

        if ($req->getMethod() == 'POST' && !empty($req->get('emailSignature'))) {
            $this->updateJournalSetting($journal, 'emailSignature', $req->get('emailSignature'), false);
        }

        $emailSignature = $journal->getSetting('emailSignature') ?
            $journal->getSetting('emailSignature')->getValue() : null;

        return $this->render('OjsJournalBundle:Manager:journal_settings_mail.html.twig',
            ['journal' => $journal, 'emailSignature' => $emailSignature]);
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function userIndexAction(Request $request)
    {
        $dispatcher = $this->get('event_dispatcher');
        $switcher = $this->createForm(new QuickSwitchType())->createView();
        $articles = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->findBy(['submitterId' => $this->getUser()->getId()]);

        $response = $response = $this->render(
            'OjsJournalBundle:User:home.html.twig',
            ['switcher' => $switcher, 'articles' => $articles]
        );

        $event = new WorkflowEvent($request);
        $dispatcher->dispatch(WorkflowEvents::LIST_ARTICLES, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        return $response;
    }
}

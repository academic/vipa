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
use Okulbilisim\CmsBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ojs\Common\Services\JournalService;
use Gedmo\Sluggable\Util\Urlizer;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step1;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step2;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step3;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step4;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step5;
use Ojs\JournalBundle\Form\Type\JournalSetup\Step6;

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

                $journalSetup = new JournalSetupProgress();
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
        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
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
                'setupId' => $setupId,
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

    /**
     * @param Request $request
     * @param $setupId
     * @param $step
     * @return mixed
     */
    public function stepControlAction(Request $request, $setupId, $step)
    {
        $stepCheckFunction = 'step'.$step.'Control';
        return $this->{$stepCheckFunction}($request, $setupId);
    }

    /**
     * Journal Setup Wizard Step 1 - Saves Journal 's step 1 data
     * @param  Request      $request
     * @param $setupId
     * @return JsonResponse
     */
    private function step1Control(Request $request, $setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step1Form = $this->createForm(new Step1(), $journal);
        $step1Form->handleRequest($request);
        if ($step1Form->isValid()) {
            $setup->setCurrentStep(2);
            $em->flush();
            return new JsonResponse(['success' => '1']);
        } else {
            return new JsonResponse(['success' => '0']);
        }
    }

    /**
     * Journal Setup Wizard Step 2 - Saves Journal 's step 2 data
     * @param  Request      $request
     * @param  null         $setupId
     * @return JsonResponse
     */
    private function step2Control(Request $request, $setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step2Form = $this->createForm(new Step2(), $journal);
        $step2Form->handleRequest($request);
        if ($step2Form->isValid()) {
            $setup->setCurrentStep(3);
            $em->flush();
            return new JsonResponse(['success' => '1']);
        } else {
            return new JsonResponse(['success' => '0']);
        }
    }

    /**
     * Journal Setup Wizard Step 3 - Saves Journal 's step 3 data
     * @param  Request      $request
     * @param  null         $setupId
     * @return JsonResponse
     */
    private function step3Control(Request $request, $setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());
        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step3Form = $this->createForm(new Step3(), $journal);
        $step3Form->handleRequest($request);
        if ($step3Form->isValid()) {
            $setup->setCurrentStep(4);
            $em->flush();
            return new JsonResponse(['success' => '1']);
        } else {
            return new JsonResponse(['success' => '0']);
        }
    }

    /**
     * Journal Setup Wizard Step 4 - Saves Journal 's step 4 data
     * @param  Request      $request
     * @param  null         $setupId
     * @return JsonResponse
     */
    private function step4Control(Request $request, $setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());
        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $setup->setCurrentStep(2);
        $data = $request->request->all();
        $pages = $data['page'];
        $twig = $this->get('okulbilisimcmsbundle.twig.post_extension');
        foreach ($pages as $page) {
            if (empty($page['title'])) {
                return new JsonResponse(['success' => '0']);
            }
            $page_ = new Post();
            $page_
                ->setStatus(1)
                ->setContent($page['content'])
                ->setObject($twig->encode($journal))
                ->setObjectId($journal->getId())
                ->setPostType('default')
                ->setTitle($page['title']);
            $em->persist($page_);
        }
        $em->flush();
        return new JsonResponse(['success' => '1']);
    }

    /**
     * Journal Setup Wizard Step 5 - Saves Journal 's step 5 data
     * @param  Request      $request
     * @param  null         $setupId
     * @return JsonResponse
     */
    private function step5Control(Request $request, $setupId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());
        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step5Form = $this->createForm(new Step5(), $journal);
        $step5Form->handleRequest($request);
        if ($step5Form->isValid()) {
            $setup->setCurrentStep(6);
            $em->flush();
            return new JsonResponse(['success' => '1']);
        } else {
            return new JsonResponse(['success' => '0']);
        }
    }

    /**
     * Journal Setup Wizard Step 6 - Saves Journal 's step 6 data
     * @param  Request      $request
     * @param $setupId
     * @return JsonResponse
     */
    private function step6Control(Request $request, $setupId)
    {
        /** @var JournalService $journalService */
        $journalService = $this->get('ojs.journal_service');
        $em = $this->getDoctrine()->getManager();
        /** @var JournalSetupProgress $setup */
        $setup = $em->getRepository('OjsJournalBundle:JournalSetupProgress')->find($setupId);
        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($setup->getJournal()->getId());

        if (!$this->isGranted('EDIT', $journal)) {
            throw new AccessDeniedException();
        }
        $step6Form = $this->createForm(new Step6(), $journal);
        $step6Form->handleRequest($request);
        if ($step6Form->isValid()) {
            $journal->setSlug(Urlizer::urlize($journal->getTitle(), '_'));
            $journal->setSetupStatus(true);
            $em->remove($setup);
            $em->flush();

            $journalLink = $journalService->generateUrl($journal);
            return new JsonResponse(
                [
                    'success' => '1',
                    'journalLink' => $journalLink,
                ]
            );
        } else {
            return new JsonResponse(['success' => '0']);
        }
    }
}

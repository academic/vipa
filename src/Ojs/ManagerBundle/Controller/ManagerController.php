<?php

namespace Ojs\ManagerBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Ojs\Common\Helper\ActionHelper;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ojs\JournalBundle\Form\JournalType;
use \Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller {

    public function journalSettingsAction($journalId = null)
    {
        if (!$journalId) {
            $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        } else {
            $em = $this->getDoctrine()->getManager();
            $journal = $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        }
        $form = $this->createForm(new JournalType(), $journal, array(
            'action' => $this->generateUrl('journal_update', array('id' => $journal->getId())),
            'method' => 'PUT',
        ));
        return $this->render('OjsManagerBundle:Manager:journal_settings.html.twig', array(
                    'journal' => $journal,
                    'form' => $form->createView(),
        ));
    }

    /**
     * 
     * @param Request $req
     * @param integer $journal
     * @param string $settingName
     * @param string $settingValue if null, funtion will return current value
     * @param boolean $encoded set tru if setting stored as json_encoded
     * @return type
     */
    private function updateJournalSetting($journal, $settingName, $settingValue, $encoded = false)
    {
        $em = $this->getDoctrine()->getManager();
        $setting = $em->
                getRepository('OjsJournalBundle:JournalSetting')->
                findOneBy(array('journal' => $journal, 'setting' => $settingName));

        $settingString = $encoded ? json_encode($settingValue) : $settingValue;
        if ($setting) {
            $setting->setValue($settingString);
        } else {
            $setting = new \Ojs\JournalBundle\Entity\JournalSetting($settingName, $settingString, $journal);
        }
        $em->persist($setting);
        $em->flush();
        return $setting ? ($encoded ? json_decode($setting->getValue()) : $setting->getValue()) : [];
    }

    public function journalSettingsSubmissionAction(Request $req, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $journal  \Ojs\JournalBundle\Entity\Journal  */
        $journal = !$journalId ?
                $this->get("ojs.journal_service")->getSelectedJournal() :
                $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        if ($req->getMethod() == 'POST' && !empty($req->get('submissionMandatoryLanguages'))) {
            $this->updateJournalSetting($journal, 'submissionMandatoryLanguages', $req->get('submissionMandatoryLanguages'), true);
        }
        if ($req->getMethod() == 'POST' && !empty($req->get('submissionAbstractTemplate'))) {
            $this->updateJournalSetting($journal, 'submissionAbstractTemplate', $req->get('submissionAbstractTemplate'), false);
        }

        $languages = $journal->getSetting('submissionMandatoryLanguages') ?
                json_decode($journal->getSetting('submissionMandatoryLanguages')->getValue()) :
                null;
        $abstractTemplate = $journal->getSetting('submissionAbstractTemplate') ?
                $journal->getSetting('submissionAbstractTemplate')->getValue() :
                null;

        $yamlParser = new \Symfony\Component\Yaml\Parser();
        $abstractTemplates = $yamlParser->parse(file_get_contents(
                        $this->container->getParameter('kernel.root_dir') .
                        '/../src/Ojs/JournalBundle/Resources/data/abstracttemplates.yml'
        ));

        return $this->render('OjsManagerBundle:Manager:journal_settings_submission.html.twig', array(
                    'journal' => $journal,
                    'submissionMandatoryLanguages' => $languages,
                    'submissionAbstractTemplate' => $abstractTemplate,
                    'abstractTemplates' => $abstractTemplates,
                    'allLanguages' => $journal->getLanguages()
        ));
    }

    public function journalSettingsMailAction(Request $req, $journalId = null)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $journal  \Ojs\JournalBundle\Entity\Journal  */
        $journal = !$journalId ?
                $this->get("ojs.journal_service")->getSelectedJournal() :
                $em->getRepository('OjsJournalBundle:Journal')->find($journalId);
        if ($req->getMethod() == 'POST' && !empty($req->get('emailSignature'))) {
            $this->updateJournalSetting($journal, 'emailSignature', $req->get('emailSignature'), false);
        }
        $emailSignature = $journal->getSetting('emailSignature')?$journal->getSetting('emailSignature')->getValue():null;

        return $this->render('OjsManagerBundle:Manager:journal_settings_mail.html.twig', array(
                    'journal' => $journal,
                    'emailSignature' => $emailSignature 
        ));
    }

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function journalSettingsPageAction()
    {
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $twig = $this->get('okulbilisimcmsbundle.twig.post_extension');
        $object = $twig->encode($journal);
        $source = new Entity("Okulbilisim\\CmsBundle\\Entity\\Post");
        $ta = $source->getTableAlias();
        $source->manipulateQuery(function(QueryBuilder $qb)use($ta,$journal,$object){
            return $qb->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq("$ta.object",":object"),
                    $qb->expr()->eq("$ta.objectId",":journalId")
                )
            )
                ->setParameters([
                    'object'=>$object,
                    'journalId'=>$journal->getId()
                ])
                ;
        });
        $grid = $this->get('grid');
        $grid->setSource($source);
        $grid->setHiddenColumns(['post_type','content','object','createdAt','updatedAt','deletedAt','objectId']); 
        $grid->addRowAction(ActionHelper::editAction('post_edit','id')); 
        $grid->addRowAction( ActionHelper::deleteAction('post_delete','id'));

        $data = [];
        $data['grid'] = $grid;
        $data['journal'] = $journal;

        return $grid->getGridResponse('OjsManagerBundle:Manager:journal_settings_pages/list.html.twig',$data);

    }
    public function userIndexAction()
    {
        $user = $this->getUser();
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $mySteps = [];
        if ($journal) {
            $dm = $this->get('doctrine_mongodb')->getManager();
            $allowedWorkflowSteps = $dm->getRepository('OjsWorkflowBundle:JournalWorkflowStep')
                    ->findBy(array('journalid' => $journal->getId()));
            // @todo we should query in a more elegant way  
            // { roles : { $elemMatch : { role : "ROLE_EDITOR" }} })
            // Don't know how to query $elemMatch 
            $security = $this->get('user.helper'); 
            foreach ($allowedWorkflowSteps as $step) {
                if (( $security->hasJournalRole('ROLE_EDITOR') || $security->hasJournalRole('ROLE_JOURNAL_MANAGER')) || $this->checkStepAndUserRoles($step)) {
                    $mySteps[] = $step;
                }
            }
        }
        $waitingTasksCount = [];
        foreach ($mySteps as $step) {
            $countQuery = $dm->getRepository('OjsWorkflowBundle:ArticleReviewStep')
                    ->createQueryBuilder('ars');
            $countQuery->field('step.$id')->equals(new \MongoId($step->getId()));
            $countQuery->field('finishedDate')->equals(null);
            $waitingTasksCount[$step->getId()] = $countQuery->count()->getQuery()->execute();
        }
        // waiting invited steps 
        $invitedWorkflowSteps = $dm->getRepository('OjsWorkflowBundle:Invitation')
                ->findBy(array('userId' => $user->getId(),'accept'=>null));

        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        }
        return $this->render('OjsManagerBundle:User:userwelcome.html.twig', array(
                    'mySteps' => $mySteps,
                    'waitingCount' => $waitingTasksCount,
                    'invitedSteps' => $invitedWorkflowSteps));
    }

    private function checkStepAndUserRoles($step)
    {
        $myRoles = $this->get('session')->get('userJournalRoles');
        $stepRoles = $step->getRoles();
        foreach ($myRoles as $myRole) {
            foreach ((array) $stepRoles as $stepRole) {
                if ($stepRole['role'] === $myRole->getRole()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * list journal users 
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data['journal'] = $this->get("ojs.journal_service")->getSelectedJournal();
        $data['entities'] = $em->getRepository('OjsUserBundle:UserJournalRole')->findAll();
        return $this->render('OjsManagerBundle:Manager:users.html.twig', $data);
    }

    public function roleUser()
    {
        $em = $this->getDoctrine()->getManager();
        $data['journal'] = $this->get("ojs.journal_service")->getSelectedJournal();
        $data['entities'] = $em->getRepository('OjsUserBundle:UserJournalRole')->findAll();
        return $this->render('OjsManagerBundle:Manager:role_users.html.twig', $data);
    }

}

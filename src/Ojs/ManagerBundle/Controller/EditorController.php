<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EditorController extends Controller
{
    /**
     * Global index page
     * @return type
     */
    public function indexAction()
    {
        return $this->render('OjsManagerBundle:Editor:index.html.twig');
    }

    /**
     *
     * Dashboard for editors
     */
    public function dashboardAction()
    {
        return $this->render('OjsManagerBundle:Editor:dashboard.html.twig',[
            'counts' => $this->counts()
        ]);
    }

    public function myJournalsAction()
    {
        $user_id = $this->getUser()->getId();
        if (!$user_id) {
            throw new HttpException(403, 'There is a problem while getting user information. Access denied');
        }
        $entities = $this->getDoctrine()->getRepository('OjsUserBundle:UserJournalRole')
                ->userJournalsWithRoles($user_id);

        return $this->render('OjsManagerBundle:Editor:myjournals.html.twig', array(
                    'entities' => $entities
        ));
    }

    public function showJournalAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $journal = $em->getRepository('OjsJournalBundle:Journal')->find($id);

        return $this->render('OjsJournalBundle:Journal:role_based/show_editor.html.twig', array('entity' => $journal));
    }

    private function counts()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $journal = $this->get("ojs.journal_service")->getSelectedJournal();
        $counts['userCount'] = $em
            ->createQuery('SELECT COUNT(a) FROM OjsUserBundle:UserJournalRole a WHERE a.journalId = :journal_id')
            ->setParameter('journal_id', $journal->getId())
            ->getSingleScalarResult();
        $counts['articleCount'] = $em
            ->createQuery('SELECT COUNT(a) FROM OjsJournalBundle:Article a WHERE a.journalId = :journal_id')
            ->setParameter('journal_id', $journal->getId())
            ->getSingleScalarResult();
        $counts['issueCount'] = $em
            ->createQuery('SELECT COUNT(a) FROM OjsJournalBundle:Issue a WHERE a.journalId = :journal_id')
            ->setParameter('journal_id', $journal->getId())
            ->getSingleScalarResult();
        return $counts;
    }

}

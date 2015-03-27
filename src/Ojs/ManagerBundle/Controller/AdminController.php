<?php

namespace Ojs\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function dashboardCheckAction()
    {
        $superAdmin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        $editor = $this->container->get('security.context')->isGranted('ROLE_EDITOR');

        if ($superAdmin) {
            return $this->redirect($this->generateUrl('dashboard_admin'));
        } elseif ($editor) {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        } else {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }
    }

    public function dashboardAction()
    {
        $super_admin = $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN');
        if ($super_admin) {
            return $this->render('OjsManagerBundle:Admin:dashboard.html.twig', [
                'counts' => $this->counts()
            ]);
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

    /**
     * returns user,article,issue counts with best performance select way
     * @return mixed
     */
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

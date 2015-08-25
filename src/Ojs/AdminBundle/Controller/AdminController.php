<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\AdminBundle\Form\Type\QuickSwitchType;
use Ojs\AnalyticsBundle\Utils\GraphDataGenerator;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function dashboardCheckAction()
    {
        if ($this->getUser()) {
            if ($this->getUser()->isAdmin()) {
                return $this->redirect($this->generateUrl('ojs_admin_dashboard'));
            } /* TODO: Redirect to journal dashboard
            elseif ($this->isGranted('VIEW', $this->get('ojs.journal_service')->getSelectedJournal())) {
                return $this->redirect($this->generateUrl('ojs_journal_dashboard_index'));
            }
            */

            else {
                return $this->redirect($this->generateUrl('ojs_user_index'));
            }
        } else {
            throw new AccessDeniedException('You are not allowed to see this page');
        }
    }

    /**
     * @return RedirectResponse|Response
     */
    public function dashboardAction()
    {
        if ($this->isGranted('VIEW', new Journal())) {
            $switcher = $this->createForm(new QuickSwitchType())->createView();
            return $this->render('OjsAdminBundle:Admin:dashboard.html.twig', ['switcher' => $switcher]);
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

    /**
     * @return RedirectResponse|Response
     */
    public function statsAction()
    {
        if ($this->isGranted('VIEW', new Journal())) {
            return $this->render('OjsAdminBundle:Admin:stats.html.twig', $this->createStats());
        } else {
            return $this->redirect($this->generateUrl('dashboard_editor'));
        }
    }

    private function createStats()
    {
        $generator = new GraphDataGenerator($this->getDoctrine()->getManager());
        $journal = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->findAll();

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($this::DATE_FORMAT, strtotime('-' . $i . ' days'));
        }

        $slicedLastMonth = array_slice($lastMonth, 1);

        $articles = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->findBy(['journal' => $journal]);

        $issues = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Issue')
            ->findBy(['journal' => $journal]);

        $json = [
            'dates' => $lastMonth,
            'articleViews' => $generator->generateArticleBarChartData($articles, $slicedLastMonth),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($issues, $slicedLastMonth),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($articles, $slicedLastMonth),
        ];

        $data = [
            'stats' => json_encode($json),
            'articles' => $generator->generateArticleViewsData($articles),
            'issueFiles' => $generator->generateIssueFileDownloadsData($issues),
            'articleFiles' => $generator->generateArticleFileDownloadsData($issues),
            'articlesMonthly' => $generator->generateArticleViewsData($articles, $slicedLastMonth),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($issues, $slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($articles, $slicedLastMonth),
        ];

        return $data;
    }
}
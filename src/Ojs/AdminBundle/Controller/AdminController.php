<?php

namespace Ojs\AdminBundle\Controller;

use Ojs\AdminBundle\Form\Type\QuickSwitchType;
use Ojs\CoreBundle\Controller\OjsController as Controller;
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
                return $this->redirectToRoute('ojs_admin_dashboard');
            } else {
                return $this->redirectToRoute('ojs_user_index');
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
            $switcher = $this->createForm(new QuickSwitchType(), null)->createView();

            return $this->render('OjsAdminBundle:Admin:dashboard.html.twig', [
                'switcher' => $switcher
                ]
            );
        } else {
            return $this->redirect($this->generateUrl('ojs_user_index'));
        }
    }

    /**
     * @return RedirectResponse|Response
     */
    public function statsAction()
    {
        $cache = $this->get('file_cache');
        if(!$cache->contains('admin_statistics')){
            $this->cacheAdminStats();
        }
        return $this->render('OjsAdminBundle:Admin:stats.html.twig', $cache->fetch('admin_statistics'));
    }

    private function cacheAdminStats()
    {
        $cache = $this->container->get('file_cache');
        $generator = $this->container->get('ojs.graph.data.generator');

        $lastMonth = ['x'];
        for($i = 0; $i < 30; $i++) {
            $lastMonth[] = date($generator->getDateFormat(), strtotime('-' . $i . ' days'));
        }
        $slicedLastMonth = array_slice($lastMonth, 1);
        $json = [
            'dates' => $lastMonth,
            'journalViews' => $generator->generateJournalBarChartData($slicedLastMonth),
            'articleViews' => $generator->generateArticleBarChartData($slicedLastMonth),
            'issueFileDownloads' => $generator->generateIssueFilePieChartData($slicedLastMonth),
            'articleFileDownloads' => $generator->generateArticleFilePieChartData($slicedLastMonth),
            'application' => $generator->generateApplicationBarChartData(),
            'doiArticle' => $generator->generateDoiArticleBarChartData(),
            'doiJournal' => $generator->generateDoiJournalBarChartData(),
        ];

        $data = [
            'stats' => json_encode($json),
            'journals' => $generator->generateJournalViewsData(),
            'articles' => $generator->generateArticleViewsData(),
            'issueFiles' => $generator->generateIssueFileDownloadsData(),
            'articleFiles' => $generator->generateArticleFileDownloadsData(),
            'journalsMonthly' => $generator->generateJournalViewsData($slicedLastMonth),
            'articlesMonthly' => $generator->generateArticleViewsData($slicedLastMonth),
            'applicationMonthly' => $generator->generateApplicationMonthlyData(),
            'applicationYearly' => $generator->generateApplicationYearlyData(),
            'doiArticleMonthly' => $generator->generateDoiArticleMonthlyData(),
            'doiArticleYearly' => $generator->generateDoiArticleYearlyData(),
            'doiJournalMonthly' => $generator->generateDoiJournalMonthlyData(),
            'doiJournalYearly' => $generator->generateDoiJournalYearlyData(),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($slicedLastMonth),
            'exitedJournal' => $generator->generateExitedJournalData(),
            'issuePublish' => $generator->generateIssuePublishCountData(),
        ];

        $cache->save('admin_statistics', $data, 1800);

        return true;
    }
}

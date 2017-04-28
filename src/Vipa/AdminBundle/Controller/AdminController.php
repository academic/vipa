<?php

namespace Vipa\AdminBundle\Controller;

use Vipa\AdminBundle\Events\StatEvent;
use Vipa\AdminBundle\Events\StatEvents;
use Vipa\AdminBundle\Form\Type\QuickSwitchType;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function dashboardCheckAction()
    {
        if ($this->getUser()) {
            if ($this->getUser()->isAdmin()) {
                return $this->redirectToRoute('vipa_admin_dashboard');
            } else {
                return $this->redirectToRoute('vipa_user_index');
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

            return $this->render('VipaAdminBundle:Admin:dashboard.html.twig', [
                'switcher' => $switcher
                ]
            );
        } else {
            return $this->redirect($this->generateUrl('vipa_user_index'));
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
        return $this->render('VipaAdminBundle:Admin:stats.html.twig', $cache->fetch('admin_statistics'));
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse|Response
     */
    public function issueAction(Request $request)
    {

        $year = (int)$request->query->get('year');
        
        if(empty($year)){
            $time = new \DateTime();
            $year = $time->format('Y');
        }
        
        $cache = $this->get('file_cache');
        if(!$cache->contains('issue_statistics_'.$year)){
            $this->cacheIssueStats($year);
        }

        $data = $cache->fetch('issue_statistics_'.$year);

        return $this->render('VipaAdminBundle:Admin:stats_issue.html.twig',[
            "year" => $year,
            "data" => $data
        ]);
    }
    
    private function cacheAdminStats()
    {
        $cache = $this->container->get('file_cache');
        $generator = $this->container->get('vipa.graph.data.generator');

        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

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
        ];

        $data = [
            'journals' => $generator->generateJournalViewsData(),
            'articles' => $generator->generateArticleViewsData(),
            'issueFiles' => $generator->generateIssueFileDownloadsData(),
            'articleFiles' => $generator->generateArticleFileDownloadsData(),
            'journalsMonthly' => $generator->generateJournalViewsData($slicedLastMonth),
            'articlesMonthly' => $generator->generateArticleViewsData($slicedLastMonth),
            'applicationMonthly' => $generator->generateApplicationMonthlyData(),
            'applicationYearly' => $generator->generateApplicationYearlyData(),
            'issueFilesMonthly' => $generator->generateIssueFileDownloadsData($slicedLastMonth),
            'articleFilesMonthly' => $generator->generateArticleFileDownloadsData($slicedLastMonth),
            'exitedJournal' => $generator->generateExitedJournalData(),
        ];
        
        $event = new StatEvent($json, $data);
        $dispatcher->dispatch(StatEvents::VIPA_ADMIN_STATS_CACHE, $event);
        
        $data = $event->getData();
        $data['stats'] = json_encode($event->getJson());
        
        
        $cache->save('admin_statistics', $data, 1800);
        
        return true;
    }

    /**
     * @param integer $year
     * @return bool
     */
    private function cacheIssueStats($year)
    {
        $cache = $this->container->get('file_cache');
        $generator = $this->container->get('vipa.graph.data.generator');

        $data = [
            'issuePublish' => $generator->generateIssuePublishCountData($year),
        ];

        $cache->save('issue_statistics_'.$year, $data, 1800);

        return true;
    }
}

<?php

namespace Vipa\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Vipa\SiteBundle\Event\SiteEvents;
use Vipa\SiteBundle\Event\ViewArticleEvent;
use Vipa\SiteBundle\Event\ViewIssueEvent;
use Vipa\SiteBundle\Event\ViewJournalEvent;
use Symfony\Component\HttpFoundation\Request;

class AnalyticsRestController extends FOSRestController
{
    /**
     * @Post("stats/article/{id}/view")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articleViewAction(Request $request, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:Article')
            ->find($id);

        $event = new ViewArticleEvent($entity);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(SiteEvents::VIEW_ARTICLE, $event);

        $token = $this->get('security.csrf.token_manager')->getToken('article_view');

        if ($request->get('token') != $token) {
            $view = $this->view(array('status' => 'error'), 403);
        } else {
            $view = $this->view(array('status' => 'ok'), 200);
        }

        return $this->handleView($view);
    }

    /**
     * @Post("stats/issue/{id}/view")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issueViewAction(Request $request, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:Issue')
            ->find($id);

        $event = new ViewIssueEvent($entity);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(SiteEvents::VIEW_ISSUE, $event);

        $token = $this->get('security.csrf.token_manager')->getToken('issue_view');

        if ($request->get('token') != $token) {
            $view = $this->view(array('status' => 'error'), 403);
        } else {
            $view = $this->view(array('status' => 'ok'), 200);
        }

        return $this->handleView($view);
    }

    /**
     * @Post("stats/journal/{id}/view")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function journalViewAction(Request $request, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:Journal')
            ->find($id);

        $event = new ViewJournalEvent($entity);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(SiteEvents::VIEW_JOURNAL, $event);

        $token = $this->get('security.csrf.token_manager')->getToken('journal_view');

        if ($request->get('token') != $token) {
            $view = $this->view(array('status' => 'error'), 403);
        } else {
            $view = $this->view(array('status' => 'ok'), 200);
        }

        return $this->handleView($view);
    }
}

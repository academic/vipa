<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\SiteBundle\Event\SiteEvents;
use Ojs\SiteBundle\Event\ViewArticleEvent;
use Ojs\SiteBundle\Event\ViewIssueEvent;
use Ojs\SiteBundle\Event\ViewJournalEvent;
use Symfony\Component\HttpFoundation\Request;

class AnalyticsRestController extends FOSRestController
{
    /**
     * @Post("stats/article/{id}/view")
     */
    public function articleViewAction(Request $request, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
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
     */
    public function issueViewAction(Request $request, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Issue')
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
     */
    public function journalViewAction(Request $request, $id)
    {
        $entity = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Journal')
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

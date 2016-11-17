<?php

namespace Ojs\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\Journal;
use Ojs\CoreBundle\Params\JournalStatuses;
use Symfony\Component\HttpFoundation\Request;

/**
 * Journal & Publisher Hosting pages controller
 * Class HostingController
 * @package Ojs\SiteBundle\Controller
 */
class HostingController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentHost = $request->getHttpHost();

        $journal = $em->getRepository(Journal::class)->findOneBy(
            array('domain' => $currentHost, 'status' => JournalStatuses::STATUS_PUBLISHED)
        );
        $this->throw404IfNotFound($journal);

        $response = $this->forward('OjsSiteBundle:Journal:journalIndex', array(
            'slug'  => $journal->getSlug(),
            'isJournalHosting' => true,
        ));

        return $response;


    }




    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function issuePageAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $currentHost = $request->getHttpHost();

        $journal = $em->getRepository(Journal::class)->findOneBy(
            array('domain' => $currentHost, 'status' => JournalStatuses::STATUS_PUBLISHED)
        );
        $this->throw404IfNotFound($journal);

        $response = $this->forward('OjsSiteBundle:Issue:issuePage', array(
            'id'  => $id,
            'isJournalHosting' => true,
        ));

        return $response;

    }

    /**
     * @param Request $request
     * @param $article_id
     * @param null $issue_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function articlePageAction(Request $request, $article_id, $issue_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $currentHost = $request->getHttpHost();

        $journal = $em->getRepository(Journal::class)->findOneBy(
            array('domain' => $currentHost, 'status' => JournalStatuses::STATUS_PUBLISHED)
        );
        $this->throw404IfNotFound($journal);


        $response = $this->forward('OjsSiteBundle:Article:articlePage', array(
            'slug' => $journal->getSlug(),
            'article_id'  => $article_id,
            'issue_id'  => $issue_id,
            'isJournalHosting' => true,
        ));

        return $response;

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function archiveIndexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $currentHost = $request->getHttpHost();

        $journal = $em->getRepository(Journal::class)->findOneBy(
            array('domain' => $currentHost, 'status' => JournalStatuses::STATUS_PUBLISHED)
        );
        $this->throw404IfNotFound($journal);

        $response = $this->forward('OjsSiteBundle:Journal:archiveIndex', array(
            'slug'  => $journal->getSlug(),
            'isJournalHosting' => true,
        ));

        return $response;
    }
}

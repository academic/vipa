<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController;
use Ojs\CoreBundle\Params\ArticleStatuses;
use Ojs\JournalBundle\Entity\Journal;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

class LockssController extends OjsController
{
    /**
     * @param $slug
     * @return Response
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        return $this->render(
            'OjsSiteBundle:Lockss:index.html.twig',
            [
                'journal' => $journal
            ]
        );
    }
    
    /**
     * @param $slug
     * @param integer $year
     * @return Response
     */
    public function volAction($slug, $year)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        return $this->render(
            'OjsSiteBundle:Lockss:vol.html.twig',
            [
                'journal'   => $journal,
                'year'      => $year
            ]
        );
    }

    /**
     * @param $slug
     * @param integer $year
     * @param string $month
     * @return Response
     */
    public function monthAction($slug, $year, $month)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Journal $journal */
        $journal = $em->getRepository('OjsJournalBundle:Journal')->findOneBy(['slug' => $slug]);
        $this->throw404IfNotFound($journal);

        $months = $this->getMonths();

        if(!in_array($month, $months)){
            $this->throw404IfNotFound(null);
        }

        $monthKey = array_search($month, $months);

        $sql = 'SELECT id,doi FROM article WHERE doi is not null AND journal_id = '.$journal->getId().' AND status = '.ArticleStatuses::STATUS_PUBLISHED.' AND EXTRACT(YEAR FROM pubdate) ='.$year;
        $sql .= 'AND EXTRACT(MONTH FROM pubdate) ='.$monthKey;

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id','id');
        $rsm->addScalarResult('doi','doi');
        $rsm->addScalarResult('doi_request_time','time');
        $query = $em->createNativeQuery($sql, $rsm);
        $articles = $query->getResult();

        return $this->render(
            'OjsSiteBundle:Lockss:month.html.twig',
            [
                'journal'   => $journal,
                'year'      => $year,
                'month'     => $month,
                'articles'  => $articles
            ]
        );
    }

    /**
     * @return array
     */
    private function getMonths()
    {
        return
            [
                '1' => 'January',
                '2' => 'February',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'June',
                '7' => 'July',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December',
            ];
    }
}
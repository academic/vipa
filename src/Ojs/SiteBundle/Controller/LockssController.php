<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController;
use Ojs\JournalBundle\Entity\Journal;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class LockssController extends OjsController
{
    /**
     * @param Journal $journal
     * @return Response
     * @ParamConverter("journal", options={"mapping": {"slug": "slug"}})
     */
    public function indexAction(Journal $journal)
    {
        return $this->render(
            'OjsSiteBundle:Lockss:index.html.twig',
            [
                'journal' => $journal
            ]
        );
    }
    
    /**
     * @param Journal $journal
     * @param integer $year
     * @return Response
     * @ParamConverter("journal", options={"mapping": {"slug": "slug"}})
     */
    public function volAction(Journal $journal, $year)
    {
        return $this->render(
            'OjsSiteBundle:Lockss:vol.html.twig',
            [
                'journal'   => $journal,
                'year'      => $year
            ]
        );
    }

    /**
     * @param Journal $journal
     * @param integer $year
     * @param string $month
     * @return Response
     * @ParamConverter("journal", options={"mapping": {"slug": "slug"}})
     */
    public function monthAction(Journal $journal, $year, $month)
    {
        return $this->render(
            'OjsSiteBundle:Lockss:month.html.twig',
            [
                'journal'   => $journal,
                'year'      => $year,
                'month'      => $month
            ]
        );
    }
}
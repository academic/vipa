<?php
/**
 * www
 */

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;

class SearchController extends Controller
{
    public function inJournal($slug)
    {
        $data = [];
        return $this->render('OjsSiteBundle:Search:in_journal.html.twig',$data);
    }
}
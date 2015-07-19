<?php

namespace Ojs\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExploreController extends Controller
{
    public function indexAction()
    {
        $data = array();
        $data['page'] = 'explore';
        return $this->render('OjsSiteBundle:Explore:explore_index.html.twig', $data);
    }

    public function institutionsIndexAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var InstitutionRepository $repo */
        $repo = $em->getRepository('OjsJournalBundle:Institution');
        $data['entities'] = $repo->getAllWithDefaultTranslation();
        $data['page'] = 'institution';

        return $this->render('OjsSiteBundle::Institution/institutions_index.html.twig', $data);
    }


    public function journalsIndexAction(Request $request, $page, $institution = null)
    {
        $searchManager = $this->get('ojs_search_manager');
        $searchManager->setPage($page);
        $filter = $request->get('filter', []);
        if ($institution) {
            /** @var Institution $institutionObj */
            $institutionObj = $this->getDoctrine()->getManager()->getRepository(
                'OjsJournalBundle:Institution'
            )->findOneBy(['slug' => $institution]);
            $filter['institution'] = $institutionObj->getId();
            $data['institutionObject'] = $institutionObj;
        }
        if (!empty($filter)) {
            $searchManager->addFilters($filter);
        }
        $result = $searchManager->searchJournal()->getResult();
        $data['result'] = $result;
        $data['total_count'] = $searchManager->getCount();
        $data['page'] = 'journals';
        $data['current_page'] = $page;
        $data['page_count'] = $searchManager->getPageCount();
        $data['aggregations'] = $searchManager->getAggregations();
        $data['filter'] = $filter;

        return $this->render('OjsSiteBundle::Journal/journals_index.html.twig', $data);
    }
}

<?php

namespace Ojs\SiteBundle\Controller;

use Elastica\Aggregation;
use Elastica\Query;
use Ojs\CoreBundle\Controller\OjsController as Controller;
use Pagerfanta\Adapter\ElasticaAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;

class PeopleController extends Controller
{
    /**
     * @param  Request $request
     * @param  int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $getRoles = $request->query->get('role_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getJournals = $request->query->get('journal_filters');
        $roleFilters = !empty($getRoles) ? explode(',', $getRoles) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $journalFilters = !empty($getJournals) ? explode(',', $getJournals) : [];

        $userSearcher = $this->get('fos_elastica.index.search.user');
        $userQuery = new Query('*');
        $userQuery->setSort(['user.fullName.raw' => 'asc']);

        if (!empty($roleFilters) || !empty($subjectFilters) || !empty($journalFilters)) {
            $boolQuery = new Query\BoolQuery();

            foreach ($roleFilters as $role) {
                $match = new Query\Match();
                $match->setField('journalUsers.roles', $role);
                $boolQuery->addMust($match);
            }

            foreach ($subjectFilters as $subject) {
                $match = new Query\Match();
                $match->setField('user.subjects.subject', $subject);
                $boolQuery->addMust($match);
            }

            foreach ($journalFilters as $journal) {
                $match = new Query\Match();
                $match->setField('journalUsers.journal.title', $journal);
                $boolQuery->addMust($match);
            }

            $userQuery->setQuery($boolQuery);
        }

        $roleAgg = new Aggregation\Terms('roles');
        $roleAgg->setField('journalUsers.roles');
        $roleAgg->setOrder('_term', 'asc');
        $roleAgg->setSize(0);
        $userQuery->addAggregation($roleAgg);

        $subjectAgg = new Aggregation\Terms('subjects');
        $subjectAgg->setField('user.subjects.subject');
        $subjectAgg->setOrder('_term', 'asc');
        $subjectAgg->setSize(0);
        $userQuery->addAggregation($subjectAgg);

        $journalAgg = new Aggregation\Terms('journals');
        $journalAgg->setField('journalUsers.journal.title');
        $journalAgg->setOrder('_term', 'asc');
        $journalAgg->setSize(0);
        $userQuery->addAggregation($journalAgg);

        $adapter = new ElasticaAdapter($userSearcher, $userQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $people = $pagerfanta->getCurrentPageResults();

        $roles = $adapter->getResultSet()->getAggregation('roles')['buckets'];
        $subjects = $adapter->getResultSet()->getAggregation('subjects')['buckets'];
        $journals = $adapter->getResultSet()->getAggregation('journals')['buckets'];

        $data = [
            'people'          => $people,
            'roles'           => $roles,
            'subjects'        => $subjects,
            'journals'        => $journals,
            'pagerfanta'      => $pagerfanta,
            'role_filters'    => $roleFilters,
            'subject_filters' => $subjectFilters,
            'journal_filters' => $journalFilters,
            'page'            => 'ojs_site_people_index',
            'search_section'  => 'user',
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}

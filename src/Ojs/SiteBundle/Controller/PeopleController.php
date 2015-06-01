<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Pagerfanta\Adapter\ElasticaAdapter;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Pagerfanta;
use Elastica\Query;
use Elastica\Aggregation\Terms;

class PeopleController extends Controller
{
    /**
     * @param Request $request
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page = 1)
    {
        $getRoles = $request->query->get('role_filters');
        $getSubjects = $request->query->get('subject_filters');
        $getJournals = $request->query->get('journal_filters');
        $roleFilters = !empty($getRoles) ?  explode(',', $getRoles) : [];
        $subjectFilters = !empty($getSubjects) ?  explode(',', $getSubjects) : [];
        $journalFilters = !empty($getJournals) ?  explode(',', $getJournals) : [];

        $roleSearcher = $this->get('fos_elastica.index.search.role');
        $userSearcher = $this->get('fos_elastica.index.search.user');
        $roleQuery = new Query();
        $userQuery = new Query();

        if (!empty($roleFilters) || !empty($journalFilters)) {
            $bool = new Query\Bool();

            foreach ($roleFilters as $role) {
                $match = new Query\Match();
                $match->setField('role.name', $role);
                $bool->addMust($match);
            }

            foreach ($journalFilters as $journal) {
                $match = new Query\Match();
                $match->setField('journal.title', $journal);
                $bool->addMust($match);
            }

            $roleQuery->setQuery($bool);
        }

        $roleAggr = new Terms('roles');
        $journalAggr = new Terms('journals');
        $roleAggr->setField('role.name');
        $journalAggr->setField('journal.title');
        $roleQuery->addAggregation($roleAggr);
        $roleQuery->addAggregation($journalAggr);

        $roleSearch = $roleSearcher->search($roleQuery);
        $roleResults = $roleSearch->getResults();

        $queryBool = new Query\Bool();
        $subjectBool = new Query\Bool();
        $usernameTerms = new Query\Terms('username');

        foreach ($roleResults as $result) {
            $username = $result->getData()['user']['username'];
            $usernameTerms->addTerm($username);
        }

        foreach ($subjectFilters as $subject) {
            $match = new Query\Match();
            $match->setField('subjects', $subject);
            $subjectBool->addMust($match);
        }

        if (!empty($roleResults))
            $queryBool->addMust($usernameTerms);
        if (!empty($subjectFilters))
            $queryBool->addMust($subjectBool);

        $userQuery->setQuery($queryBool);

        $subjectAggr = new Terms('subjects');
        $subjectAggr->setField('subjects');
        $userQuery->addAggregation($subjectAggr);

        $roles = $roleSearch->getAggregation('roles')['buckets'];
        $journals = $roleSearch->getAggregation('journals')['buckets'];

        $adapter = new ElasticaAdapter($userSearcher, $userQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $people = $pagerfanta->getCurrentPageResults();

        $subjects = $adapter->getResultSet()->getAggregation('subjects')['buckets'];

        $data = [
            'people' => $people,
            'roles' => $roles,
            'journals' => $journals,
            'subjects' => $subjects,
            'pagerfanta' => $pagerfanta,
            'role_filters' => $roleFilters,
            'journal_filters' => $journalFilters,
            'subject_filters' => $subjectFilters,
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}

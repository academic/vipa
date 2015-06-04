<?php

namespace Ojs\SiteBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\JournalBundle\Entity\SubjectRepository;
use Ojs\UserBundle\Entity\RoleRepository;
use Pagerfanta\Adapter\ElasticaAdapter;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Pagerfanta;
use Elastica\Query;

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
        $roleFilters = !empty($getRoles) ? explode(',', $getRoles) : [];
        $subjectFilters = !empty($getSubjects) ? explode(',', $getSubjects) : [];
        $journalFilters = !empty($getJournals) ? explode(',', $getJournals) : [];

        $userSearcher = $this->get('fos_elastica.index.search.user');
        $userQuery = new Query('*');

        if (!empty($roleFilters) || !empty($subjectFilters) || !empty($journalFilters)) {
            $boolQuery = new Query\Bool();

            foreach ($roleFilters as $role) {
                $match = new Query\Match();
                $match->setField('userJournalRoles.role.name', $role);
                $boolQuery->addMust($match);
            }

            foreach ($subjectFilters as $subject) {
                $match = new Query\Match();
                $match->setField('subjects', $subject);
                $boolQuery->addMust($match);
            }

            foreach ($journalFilters as $journal) {
                $match = new Query\Match();
                $match->setField('userJournalRoles.journal.title', $journal);
                $boolQuery->addMust($match);
            }

            $userQuery->setQuery($boolQuery);
        }

        $adapter = new ElasticaAdapter($userSearcher, $userQuery);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $people = $pagerfanta->getCurrentPageResults();

        $roles = $this->getDoctrine()->getRepository('OjsUserBundle:Role')->getAllNames();
        $subjects = $this->getDoctrine()->getRepository('OjsJournalBundle:Subject')->getAllSubjects();
        $journals = $this->getDoctrine()->getRepository('OjsJournalBundle:Journal')->getAllTitles();

        $data = [
            'people' => $people,
            'roles' => $roles,
            'subjects' => $subjects,
            'journals' => $journals,
            'pagerfanta' => $pagerfanta,
            'role_filters' => $roleFilters,
            'subject_filters' => $subjectFilters,
            'journal_filters' => $journalFilters,
        ];

        return $this->render('OjsSiteBundle:People:index.html.twig', $data);
    }
}

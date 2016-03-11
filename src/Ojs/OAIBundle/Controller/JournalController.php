<?php

namespace Ojs\OAIBundle\Controller;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Ojs\CoreBundle\Helper\StringHelper;
use Ojs\CoreBundle\Params\ArticleStatuses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JournalController extends OAIController
{
    /**
     * Action for the identify verb
     * @param Request $request
     * @return Response
     */
    public function identifyAction(Request $request)
    {
        return $this->response("OjsOAIBundle:Default:identify.xml.twig");
    }

    /**
     * Action for the records verb
     * @param Request $request
     * @return Response
     */
    public function recordsAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $slug = $request->get('slug', false);
        $setParam = $request->get('set', false);
        $fromParam = $request->get('from', false);
        $untilParam = $request->get('until', false);

        $this->throw404IfNotFound($slug);

        $builder = $em->createQueryBuilder();
        $builder->select('article')->from('OjsJournalBundle:Article', 'article');
        $builder->join('article.journal', 'journal', 'WITH');

        $builder->where($builder->expr()->eq('article.status', ArticleStatuses::STATUS_PUBLISHED));
        $builder->andWhere($builder->expr()->eq('journal.slug', ':slug'))->setParameter('slug', $slug);

        if ($fromParam) {
            $from = new \DateTime();
            $from->setTimestamp(strtotime($fromParam));

            $comparison = $builder->expr()->gte('article.created', ':from');
            $builder->andWhere($comparison)->setParameter('from', $from);
        }

        if ($untilParam) {
            $until = new \DateTime();
            $until->setTimestamp(strtotime($until));

            $comparison = $builder->expr()->lte('article.created', ':until');
            $builder->andWhere($comparison)->setParameter('until', $until);
        }

        $session = $this->get('session');
        $resumptionToken = $request->get('resumptionToken');

        if ($resumptionToken) {
            $token = $session->get($resumptionToken);
            $currentPage = (int) $token['page'];
            $setParam = $token['set'];
        } else {
            $currentPage = 1;
        }

        $generatedToken = md5(StringHelper::generateKey());
        $session->set($generatedToken, $currentPage + 1);

        if ($setParam) {
            $builder->join('article.section', 'section', 'WITH');
            $condition = $builder->expr()->eq('section.id', ':section');
            $builder->andWhere($condition)->setParameter('section', $setParam);
        }

        $paginator = $this->get('knp_paginator');
        /** @var AbstractPagination $records */
        $records = $paginator->paginate($builder->getQuery(), $currentPage, 100);

        $data = [
            'specType' => 'section',
            'records' => $records,
            'currentPage' => $currentPage,
            'resumptionToken' => $generatedToken,
            'metadataPrefix' => $request->get('metadataPrefix','oai_dc'),
            'isLast' => $records->getTotalItemCount() >= $currentPage * 100,
        ];

        return $this->response('OjsOAIBundle:Default:records.xml.twig', $data);
    }

    /**
     * Action for the list sets verb
     * @param Request $request
     * @return Response
     */
    public function listSetsAction(Request $request)
    {
        // TODO: Implement listSetsAction() method.
    }

    /**
     * Action for the list identifier verb
     * @param Request $request
     * @return Response
     */
    public function listIdentifierAction(Request $request)
    {
        // TODO: Implement listIdentifierAction() method.
    }

    /**
     * Action for the get record verb
     * @param Request $request
     * @return Response
     */
    public function getRecordAction(Request $request)
    {
        // TODO: Implement getRecordAction() method.
    }
}

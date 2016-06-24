<?php

namespace Ojs\OAIBundle\Controller;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Ojs\CoreBundle\Helper\StringHelper;
use Ojs\CoreBundle\Params\ArticleStatuses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $fileCache = $this->get('file_cache');
        $resumptionToken = $request->get('resumptionToken');

        if ($resumptionToken) {
            $token = $fileCache->fetch($resumptionToken);
            $currentPage = (int) $token['page'];
            $setParam = $token['set'];
        } else {
            $currentPage = 1;
        }

        $generatedToken = md5(StringHelper::generateKey());
        $fileCache->save($generatedToken, $currentPage + 1, 60*60*24);

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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $slug = $request->get('slug', false);
        $this->throw404IfNotFound($slug);

        $builder = $em->createQueryBuilder();
        $builder->select('section')->from('OjsJournalBundle:Section', 'section');
        $builder->join('section.journal', 'journal', 'WITH');

        $builder->where($builder->expr()->eq('journal.slug', ':slug'))->setParameter('slug', $slug);

        $fileCache = $this->get('file_cache');
        $resumptionToken = $request->get('resumptionToken');

        if ($resumptionToken) {
            $token = $fileCache->fetch($resumptionToken);
            $currentPage = (int) $token['page'];
        } else {
            $currentPage = 1;
        }

        $generatedToken = md5(StringHelper::generateKey());

        $paginator = $this->get('knp_paginator');
        /** @var AbstractPagination $records */
        $records = $paginator->paginate($builder->getQuery(), $currentPage, 100);

        $data = [
            'records' => $records,
            'currentPage' => $currentPage,
            'resumptionToken' => $generatedToken,
            'isLast' => $records->getTotalItemCount() >= $currentPage * 100,
        ];

        return $this->response('OjsOAIBundle:Journal:sets.xml.twig', $data);
    }

    /**
     * Action for the list identifier verb
     * @param Request $request
     * @return Response
     */
    public function listIdentifierAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $slug = $request->get('slug', false);
        $this->throw404IfNotFound($slug);

        $builder = $em->createQueryBuilder();
        $builder->select('article')->from('OjsJournalBundle:Article', 'article');
        $builder->join('article.journal', 'journal', 'WITH');
        $builder->where($builder->expr()->eq('journal.slug', ':slug'))->setParameter('slug', $slug);

        $data = ['records' => $builder->getQuery()->getResult()];

        return $this->response('OjsOAIBundle:Journal:identifiers.xml.twig', $data);
    }

    /**
     * Action for the get record verb
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRecordAction(Request $request)
    {
        $identifier = $request->get('identifier');
        $baseHost = $this->container->getParameter("base_host");
        preg_match_all('~oai:'.$baseHost.':((\barticle\b)|(\brecord\b))/(\d+)~', $identifier, $matches);

        if (!isset($matches[4]) || !isset($matches[4][0])) {
            throw new NotFoundHttpException("Record not found.");
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $builder = $em->createQueryBuilder();
        $builder->select("article")->from("OjsJournalBundle:Article", "article");
        $builder->where($builder->expr()->eq("article.id", ":id"))->setParameter("id", $matches[4][0]);

        $data = [];
        $data['metadataPrefix'] = $request->get('metadataPrefix', 'oai_dc');
        $data['record'] = $builder->getQuery()->getOneOrNullResult();

        if (!$data['record']) {
            throw new NotFoundHttpException("Record not found");
        }

        return $this->response('OjsOAIBundle:Default:record.xml.twig', $data);
    }
}

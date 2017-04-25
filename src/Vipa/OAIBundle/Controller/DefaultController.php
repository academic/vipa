<?php

namespace Vipa\OAIBundle\Controller;

use Doctrine\ORM\EntityManager;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\CoreBundle\Helper\StringHelper;
use Vipa\CoreBundle\Params\ArticleStatuses;
use Vipa\CoreBundle\Params\IssueDisplayModes;
use Vipa\CoreBundle\Params\JournalStatuses;
use Vipa\JournalBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package Vipa\OAIBundle\Controller
 */
class DefaultController extends OAIController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function identifyAction(Request $request)
    {
        return $this->response("VipaOAIBundle:Default:identify.xml.twig");
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function recordsAction(Request $request)
    {
        $fileCache = $this->get('file_cache');

        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $from = $request->get('from', false);
        $until = $request->get('until', false);
        $set = $request->get('set',false);
        $qb = $em->createQueryBuilder();
        $qb->select("a")
            ->from("VipaJournalBundle:Article", 'a');
        $qb->andWhere('a.status = '. ArticleStatuses::STATUS_PUBLISHED);
        if ($from) {
            $_from = new \DateTime();
            $_from->setTimestamp(strtotime($from));
            $qb->where(
                $qb->expr()->gte('a.created', ':from')
            )
                ->setParameter('from', $_from);
        }
        if ($until) {
            $_until = new \DateTime();
            $_until->setTimestamp(strtotime($until));
            $condition = $qb->expr()->lte('a.created', ':until');
            if ($from) {
                $qb->andWhere(
                    $condition
                );
            } else {
                $qb->where(
                    $condition
                );
            }
            $qb->setParameter('until', $_until);
        }

        $currentPage = 1;
        if($request->query->has('resumptionToken') && $fileCache->contains($request->get('resumptionToken'))){
            $token = $fileCache->fetch($request->query->get('resumptionToken'));
            $currentPage = (int)$token['page'];
            $set = $token['set'];
        }

        if($set){
            $qb->join('a.journal','j','WITH');
            $qb->andWhere(
                $qb->expr()->eq('j.slug',':slug')
            )
                ->setParameter('slug',$set);
        }

        $paginator = $this->get('knp_paginator');

        $records = $paginator->paginate(
            $qb->getQuery(),
            $currentPage,
            100
        );
        $data['records'] = $records;
        $key = md5(StringHelper::generateKey());
        $fileCache->save($key, [
            'page'=>$currentPage + 1,
            'set' => $set
        ], 60*60*24);
        $data['resumptionToken'] = $key;
        $data['isLast'] = $records->getTotalItemCount() >= $currentPage * 100 ? true:false;
        $data['currentPage'] = $currentPage;
        $data['metadataPrefix'] = $request->get('metadataPrefix', 'oai_dc');
        $data['displayModes'] = IssueDisplayModes::getDisplayModes();
        return $this->response('VipaOAIBundle:Default:records.xml.twig', $data);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function listSetsAction(Request $request)
    {
        $fileCache = $this->get('file_cache');

        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $from = $request->get('from', false);
        $until = $request->get('until', false);
        $qb = $em->createQueryBuilder();
        $qb->select("j")
            ->from("VipaJournalBundle:Journal", 'j');
        $qb->andWhere('j.status = '. JournalStatuses::STATUS_PUBLISHED);
        if ($from) {
            $_from = new \DateTime();
            $_from->setTimestamp(strtotime($from));
            $qb->where(
                $qb->expr()->gte('j.created', ':from')
            )
                ->setParameter('from', $_from);
        }
        if ($until) {
            $_until = new \DateTime();
            $_until->setTimestamp(strtotime($until));
            $condition = $qb->expr()->lte('j.created', ':until');
            if ($from) {
                $qb->andWhere(
                    $condition
                );
            } else {
                $qb->where(
                    $condition
                );
            }
            $qb->setParameter('until', $_until);
        }
        $paginator = $this->get('knp_paginator');
        $resumptionToken = $request->get('resumptionToken');
        if($resumptionToken){
            $currentPage = (int)$fileCache->fetch($resumptionToken);
        }else{
            $currentPage = 1;
        }
        $sets = $paginator->paginate(
            $qb->getQuery(),
            $currentPage,
            100
        );
        $data['records'] = $sets;
        $key = md5(StringHelper::generateKey());
        $fileCache->save($key, $currentPage+1, 60*60*24);
        $data['resumptionToken'] = $key;
        $data['isLast'] = $sets->getTotalItemCount()>=$currentPage*100?true:false;
        $data['currentPage'] = $currentPage;
        $data['metadataPrefix'] = $request->get('metadataPrefix','oai_dc');

        return $this->response('VipaOAIBundle:Default:sets.xml.twig', $data);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function listIdentifierAction(Request $request)
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("a")
            ->from("VipaJournalBundle:Article", 'a');
        $qb->andWhere('a.status = '.ArticleStatuses::STATUS_PUBLISHED);
        $set = $request->get("set", false);
        if ($set) {
            $qb->join("a.journal","j","WITH");
            $qb->where(
                $qb->expr()->gte('j.slug', ':slug')
            )
                ->setParameter('slug', $set);
        }
        $data['articles'] = $qb->getQuery()->getResult();
        return $this->response("VipaOAIBundle:Default:identifiers.xml.twig", $data);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRecordAction(Request $request)
    {
        $data = [];
        $identifier = $request->get('identifier');
        $base_host = $this->container->getParameter("base_host");
        preg_match_all('~oai:'.$base_host.':((\barticle\b)|(\brecord\b))/(\d+)~',$identifier,$matches);
        if(!isset($matches[4]) || !isset($matches[4][0])){
            throw new NotFoundHttpException("Record not found.");
        }
        $id = $matches[4][0];
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("a")
            ->from("VipaJournalBundle:Article","a");
        $qb->where(
            $qb->expr()->eq("a.id",":id")
        )
            ->setParameter("id",$id);
        $qb->andWhere(
            $qb->expr()->eq("a.status",ArticleStatuses::STATUS_PUBLISHED)
        );
        $data['record'] = $qb->getQuery()->getOneOrNullResult();
        if(!$data['record']){
            throw new NotFoundHttpException("Record not found");
        }
        $data['metadataPrefix'] = $request->get('metadataPrefix','oai_dc');
        return $this->response('VipaOAIBundle:Default:record.xml.twig',$data);
    }
}

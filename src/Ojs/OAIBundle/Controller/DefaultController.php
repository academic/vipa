<?php

namespace Ojs\OAIBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\Common\Helper\StringHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @param  Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $verb = $request->get('verb');
        switch ($verb) {
            case 'Identify':
                return $this->identifyAction($request);
            case 'ListRecords':
                return $this->recordsAction($request);
            case 'ListSets':
                return $this->listSetsAction($request);
            case 'ListMetadataFormats':
                return $this->listMetadataFormatsAction($request);
            case 'ListIdentifiers':
                return $this->listIdentifierAction($request);
            case 'GetRecord':
                return $this->getRecordAction($request);
        }
        return $this->response('OjsOAIBundle:Default:index.xml.twig');
    }

    /**
     * @return Response
     */
    public function identifyAction()
    {
        return $this->response("OjsOAIBundle:Default:identify.xml.twig");
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function recordsAction(Request $request)
    {
        $session = $this->get('session');

        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $from = $request->get('from', false);
        $until = $request->get('until', false);
        $set = $request->get('set',false);
        $qb = $em->createQueryBuilder();
        $qb->select("a")
            ->from("OjsJournalBundle:Article", 'a');
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

        $resumptionToken = $request->get('resumptionToken');
        if($resumptionToken){
            $token = $session->get($resumptionToken);
            $currentPage = (int)$token['page'];
            $set = $token['set'];
        }else{
            $currentPage = 1;
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
        $session->set($key, [
            'page'=>$currentPage+1,
            'set' => $set
        ]);
        $data['resumptionToken'] = $key;
        $data['isLast'] = $records->getTotalItemCount()>=$currentPage*100?true:false;
        $data['currentPage'] = $currentPage;
        $data['metadataPrefix'] = $request->get('metadataPrefix','oai_dc');

        return $this->response('OjsOAIBundle:Default:records.xml.twig', $data);
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function listSetsAction(Request $request)
    {
        $session = $this->get('session');

        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $from = $request->get('from', false);
        $until = $request->get('until', false);
        $qb = $em->createQueryBuilder();
        $qb->select("j")
            ->from("OjsJournalBundle:Journal", 'j');
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
            $currentPage = (int)$session->get($resumptionToken);
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
        $session->set($key, $currentPage+1);
        $data['resumptionToken'] = $key;
        $data['isLast'] = $sets->getTotalItemCount()>=$currentPage*100?true:false;
        $data['currentPage'] = $currentPage;
        $data['metadataPrefix'] = $request->get('metadataPrefix','oai_dc');

        return $this->response('OjsOAIBundle:Default:sets.xml.twig', $data);
    }

    /**
     * @return Response
     */
    public function listMetadataFormatsAction()
    {
        return $this->response('OjsOAIBundle:Default:metadata_formats.xml.twig');
    }

    /**
     * @return Response
     */
    public function listIdentifierAction(Request $request)
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select("a")
            ->from("OjsJournalBundle:Article", 'a');
        $set = $request->get("set", false);
        if ($set) {
            $qb->join("a.journal","j","WITH");
            $qb->where(
                $qb->expr()->gte('j.slug', ':slug')
            )
                ->setParameter('slug', $set);
        }
        $data['articles'] = $qb->getQuery()->getResult();
        return $this->response("OjsOAIBundle:Default:identifiers.xml.twig", $data);
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
            ->from("OjsJournalBundle:Article","a");
        $qb->where(
            $qb->expr()->eq("a.id",":id")
        )
            ->setParameter("id",$id);
        $data['record'] = $qb->getQuery()->getOneOrNullResult();
        if(!$data['record']){
            throw new NotFoundHttpException("Record not found");
        }
        $data['metadataPrefix'] = $request->get('metadataPrefix','oai_dc');
        return $this->response('OjsOAIBundle:Default:record.xml.twig',$data);
    }

    /**
     * Xml response
     * @param $template
     * @param array $data
     * @return Response
     */
    private function response($template, $data = [])
    {
        $response = new Response();
        $response->headers->set('content-type', 'text/xml');
        return $this->render($template, $data, $response);
    }
}

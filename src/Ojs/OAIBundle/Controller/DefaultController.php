<?php

namespace Ojs\OAIBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ojs\Common\Controller\OjsController as Controller;
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
                break;
            case 'ListRecords':
                return $this->recordsAction($request);
                break;
            case 'ListSets':
                return $this->listSetsAction($request);
                break;
            case 'ListMetadataFormats':
                return $this->listMetadataFormatsAction($request);
                break;
            case 'ListIdentifiers':
                return $this->listIdentifierAction($request);
                break;
            case 'GetRecord':
                return $this->getRecordAction($request);
                break;
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
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $from = $request->get('from', false);
        $until = $request->get('until', false);
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
        $records = $qb->getQuery()->getResult();
        $data['records'] = $records;

        return $this->response('OjsOAIBundle:Default:records.xml.twig', $data);
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function listSetsAction(Request $request)
    {
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
        $sets = $qb->getQuery()->getResult();
        $data['records'] = $sets;

        return $this->response('OjsOAIBundle:Default:sets.xml.twig', $data);
    }

    /**
     * @return Response
     */
    public function listMetadataFormatsAction()
    {
        return new Response();
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
        return $this->response('OjsOAIBundle:Default:record.xml.twig',$data);
    }

    /**
     * Xml response
     * @param $template
     * @param array $data
     * @return Response
     */
    public function response($template, $data = [])
    {
        $response = new Response();
        $response->headers->set('content-type', 'text/xml');
        return $this->render($template, $data, $response);
    }
}

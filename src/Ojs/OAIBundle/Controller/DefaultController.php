<?php

namespace Ojs\OAIBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $verb = $request->get('verb');
        switch($verb){
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
        }
        return $this->render('OjsOAIBundle:Default:index.html.twig');
    }

    public function identifyAction()
    {
        return new Response();
    }

    public function recordsAction(Request $request)
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $from = $request->get('from',false);
        $until = $request->get('until',false);
        $qb = $em->createQueryBuilder();
        $qb->select("a")
            ->from("OjsJournalBundle:Article",'a')
        ;
        if($from){
            $_from = new \DateTime();
            $_from->setTimestamp(strtotime($from));
            $qb->where(
                $qb->expr()->gte('a.created',':from')
            )
                ->setParameter('from',$_from)
            ;
        }
        if($until){
            $_until = new \DateTime();
            $_until->setTimestamp(strtotime($until));
            $condition = $qb->expr()->lte('a.created',':until');
            if($from){
                $qb->andWhere(
                    $condition
                );
            }else{
                $qb->where(
                    $condition
                );
            }
            $qb->setParameter('until',$_until);
        }
        $records = $qb->getQuery()->getResult();
        $data['records'] = $records;
        return $this->render('OjsOAIBundle:Default:records.html.twig', $data);
    }

    public function listSetsAction(Request $request)
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $from = $request->get('from',false);
        $until = $request->get('until',false);
        $qb = $em->createQueryBuilder();
        $qb->select("j")
            ->from("OjsJournalBundle:Journal",'j')
        ;
        if($from){
            $_from = new \DateTime();
            $_from->setTimestamp(strtotime($from));
            $qb->where(
                $qb->expr()->gte('j.created',':from')
            )
                ->setParameter('from',$_from)
            ;
        }
        if($until){
            $_until = new \DateTime();
            $_until->setTimestamp(strtotime($until));
            $condition = $qb->expr()->lte('j.created',':until');
            if($from){
                $qb->andWhere(
                    $condition
                );
            }else{
                $qb->where(
                    $condition
                );
            }
            $qb->setParameter('until',$_until);
        }
        $sets = $qb->getQuery()->getResult();
        $data['records'] = $sets;
        return $this->render('OjsOAIBundle:Default:sets.html.twig',$data);
    }

    public function listMetadataFormatsAction()
    {
        return new Response();
    }

    public function listIdentifierAction()
    {
        return new Response();
    }
}

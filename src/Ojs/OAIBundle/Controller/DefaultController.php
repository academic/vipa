<?php

namespace Ojs\OAIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjsOAIBundle:Default:index.html.twig');
    }

    public function identifiersAction()
    {
        return new Response();
    }

    public function recordsAction()
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $records = $em->getRepository('OjsJournalBundle:Article')->findAll();
        $data['records'] = $records;
        return $this->render('OjsOAIBundle:Default:records.html.twig', $data);
    }

    public function listSetsAction()
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $sets = $em->getRepository('OjsJournalBundle:Journal')->findAll();
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

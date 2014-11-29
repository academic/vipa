<?php

namespace Ojs\WikiBundle\Controller;

use Ojs\WikiBundle\Entity\Page;
use Ojs\WikiBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OjsWikiBundle:Default:index.html.twig');
    }

    public function detailAction(Request $request, $object, $type, $slug)
    {
        $data = [];
        $data['content'] = $this->getDoctrine()->getManager()->getRepository('OjsWikiBundle:Page')->findOneBy(['slug' => $slug]);
        return $this->render('OjsWikiBundle:Default:detail.html.twig', $data);
    }
}

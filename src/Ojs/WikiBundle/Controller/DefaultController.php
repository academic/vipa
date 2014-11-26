<?php

namespace Ojs\WikiBundle\Controller;

use Ojs\WikiBundle\Entity\Page;
use Ojs\WikiBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OjsWikiBundle:Default:index.html.twig', array('name' => $name));
    }


    public function detailAction(Request $request, $object, $type, $slug)
    {
        $data = [];
        $data['content'] = $this->getDoctrine()->getManager()->getRepository('OjsWikiBundle:Page')->findOneBy(['slug' => $slug]);
        return $this->render('OjsWikiBundle:Default:detail.html.twig', $data);
    }

    private function getObject($object, $type)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        switch ($type) {
            case 'journal':
                $object = $em->find('OjsJournalBundle:Journal', $object);
                if (!$object)
                    throw new NotFoundHttpException("Journal not found!");
                break;
            default:
                throw new NotFoundHttpException("Content type not found!");
                break;
        }
        return $object;
    }
}

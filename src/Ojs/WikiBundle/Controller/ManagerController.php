<?php
/**
 * Date: 26.11.14
 * Time: 09:56
 * Devs: [
 *   ]
 */

namespace Ojs\WikiBundle\Controller;


use Ojs\JournalBundle\Entity\Journal;
use Ojs\WikiBundle\Entity\Page;
use Ojs\WikiBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ManagerController extends Controller
{
    public function listAction(Request $request, $type, $object)
    {
        $data = [];
        $data['type'] = $type;
        $data['object'] = $this->getObject($object, $type);
        $data['entities'] = $this->getPages($data['object']);
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();


        return $this->render('OjsWikiBundle:Manager:list.html.twig', $data);
    }

    public function createAction(Request $request, $object, $type, $id = 0)
    {
        $data = [];
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();


        $page = $id ? $em->find('OjsWikiBundle:Page', $id) : new Page();

        $form = $this->createForm(new PageType(), $page, ['object_id' => $object, 'object_type' => $type]);

        $data['object'] = $this->getObject($object, $type);

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $page->setJournal($this->getObject($object, $type));
                $em->persist($page);
                $em->flush();
                return $this->redirect($this->get('router')->generate('ojs_wiki_object_pages', ['object' => $object, 'type' => $type]));
            } else {
                echo 'diil'; //@todo
            }
            exit;
        }
        $data['form'] = $form->createView();
        $data['type'] = $type;
        return $this->render('OjsWikiBundle:Manager:create.html.twig', $data);
    }

    public function deleteAction(Request $request, $object, $type, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $page = $em->find('OjsWikiBundle:Page', $id);
        if (!$page)
            $this->redirect($this->get('router')->generate('ojs_wiki_object_pages', ['object' => $object, 'type' => $type]));
        $em->remove($page);
        $em->flush();
        return $this->redirect($this->get('router')->generate('ojs_wiki_object_pages', ['object' => $object, 'type' => $type]));
    }

    private function getObject($object_id, $type)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        switch ($type) {
            case 'journal':
                $entity = $em->find('OjsJournalBundle:Journal', $object_id);
                $role = $em->getRepository('OjsUserBundle:Role')->findOneBy(['role' => 'ROLE_JOURNAL_MANAGER']);
                if (!$em->getRepository('OjsUserBundle:User')->hasJournalRole($this->getUser(), $role, $entity))
                    throw new AccessDeniedException;
                break;
            default:
                throw new NotFoundHttpException("Undefined object type");
                break;
        }

        return $entity;
    }

    private function getPages($object)
    {
        $case = [];
        if ($object instanceof Journal)
            $case = ['journal_id' => $object->getId()];
        $entities = $this->getDoctrine()->getManager()->getRepository('OjsWikiBundle:Page')->findBy($case);
        return $entities;
    }
} 
<?php
/**
 * Date: 17.01.15
 * Time: 23:24
 */

namespace Ojs\UserBundle\Controller;


use Ojs\UserBundle\Document\AnonymUser;
use Ojs\UserBundle\Form\AnonymUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnonymUserController extends Controller
{
    public function createAction(Request $request, $object = null, $id = 0)
    {
        $data = [];
        $user = new \Ojs\UserBundle\Document\AnonymUser();
        $user->setObject($object);
        $user->setObjectId($id);
        $form = $this->createCreateForm($user);
        $data['form'] = $form->createView();
        return $this->render('OjsUserBundle:AnonymUser:create.html.twig', $data);
    }

    public function createSuccessAction(Request $request)
    {
        $entity = new \Ojs\UserBundle\Document\AnonymUser();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dm = $this->container->get('doctrine.odm.mongodb.document_manager');
            $dm->persist($entity);
            $dm->flush();
            return $this->redirect($this->generateUrl('user_list_anonym_login', array('id' => $entity->getObjectId(), 'object' => $entity->getObject())));
        }
        $data['form'] = $form->createView();
        return $this->render('OjsUserBundle:AnonymUser:create.html.twig', $data);

    }

    public function createCreateForm(AnonymUser $entity)
    {
        $form = $this->createForm(
            new AnonymUserType(),
            $entity,
            [
                'action' => $this->get('router')->generate('user_create_anonym_login_success'),
                'method' => 'POST'
            ]);
        return $form;
    }

    public function listAction(Request $request, $object, $id)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager')
            ->getRepository('OjsUserBundle:AnonymUser');
        $users = $dm->findBy(['object' => $object, 'object_id' => (int)$id]);
        $data = [];
        $data['entities'] =$users;
        return $this->render('OjsUserBundle:AnonymUser:index.html.twig', $data);
    }
}
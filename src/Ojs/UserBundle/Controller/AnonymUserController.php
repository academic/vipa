<?php
/**
 * Date: 17.01.15
 * Time: 23:24
 */

namespace Ojs\UserBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Ojs\UserBundle\Document\AnonymUser;
use Ojs\UserBundle\Document\AnonymUserToken;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Form\AnonymUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnonymUserController extends Controller
{
    public function createAction(Request $request, $object = null, $id = 0)
    {
        $data = [];
        $user = new User();
        $form = $this->createCreateForm($user);
        $data['form'] = $form->createView();
        return $this->render('OjsUserBundle:AnonymUser:create.html.twig', $data);
    }

    public function createSuccessAction(Request $request)
    {
        /**
         * 1- Create user
         * 2- Add roles
         * 3- Set token for one click login
         * 4- Send information email
         */
        $user_formdata = $request->request->get('anonym_user');
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['email' => $user_formdata['email']]);

        $entity = $user ? $user : new \Ojs\UserBundle\Entity\User();

        $entity
            ->setStatus(1);
        if(!$entity->getPassword()){
            $entity
                ->setPassword(time());
        }
        if(!$entity->getUsername()){
            $entity
                ->setUsername($user_formdata['email']);
        }
        $roles = $entity->getRoles();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {

            //Add extra roles to user
            foreach ($roles as $role) {
                if (!(new ArrayCollection($entity->getRoles()))->contains($role)) {
                    $entity->addRole($role);
                }
            }

            //Add default user role if not exists
            $userrole = $em->getRepository('OjsUserBundle:Role')->findOneBy(['role'=>'ROLE_USER']);
            if(!(new ArrayCollection($entity->getRoles()))->contains($userrole)){
                $entity->addRole($userrole);
            }

            //persist user
            $em->persist($entity);
            $em->flush();



            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            //Create anon token
            $anonymUser = new AnonymUserToken();
            $anonymUser->setToken(md5($entity->getEmail()))
                ->setUserId($entity->getId())
                ->setUsed(false);
            //Persist anon token
            $dm->persist($anonymUser);
            $dm->flush();
            //send email to user

            $msgBody = $this->renderView(
                'OjsUserBundle:Mails:User/invitationEmail.html.twig', array('user' => $user,'sender'=>$this->getUser(),'hash'=>md5($entity->getEmail()))
            );

            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                ->setSubject('Ojs Invite')
                ->setFrom($this->container->getParameter('system_email'))
                ->setTo($entity->getEmail())
                ->setBody($msgBody)
                ->setContentType('text/html');
            $mailer->send($message);

            return $this->redirect($this->generateUrl('user_edit_anonym_login', array('id' => $entity->getId())));
        }
        $data['form'] = $form->createView();

        return $this->render('OjsUserBundle:AnonymUser:create.html.twig', $data);

    }
    public function createCreateForm(User $entity)
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


    public function createEditForm(User $entity)
    {
        $form = $this->createForm(
            new AnonymUserType(),
            $entity,
            [
                'action' => $this->get('router')->generate('user_edit_anonym_login_success',['id'=>$entity->getId()]),
                'method' => 'POST'
            ]);
        return $form;
    }

    public function listAction(Request $request, $object=null, $id=0)
    {
        $dm = $this->container->get('doctrine.odm.mongodb.document_manager')
        ->getRepository('OjsUserBundle:AnonymUserToken');
        $params = [];
        if($object)
            $params['object']=$object;
        if($id)
            $params['object_id']=(int)$id;
        $users = $dm->findBy($params);
        $data = [];
        $data['entities'] = $users;
        //@todo object based roles not stable.
        return $this->render('OjsUserBundle:AnonymUser:index.html.twig', $data);
    }

    public function editAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('OjsUserBundle:User', $id);
        $form = $this->createEditForm($user);
        $data = [];
        $data['form'] = $form->createView();
        return $this->render('OjsUserBundle:AnonymUser:create.html.twig', $data);
    }

    public function editSuccessAction(Request $request, $id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $entity = $em->find('OjsUserBundle:User',$id);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
            //send email to user
            return $this->redirect($this->generateUrl('user_edit_anonym_login', array('id' => $entity->getId())));
        }
        $data['form'] = $form->createView();

        return $this->render('OjsUserBundle:AnonymUser:create.html.twig', $data);
    }

    public function deleteAction(Request $request, $id)
    {
        $login = $this->container->get('doctrine.odm.mongodb.document_manager')
            ->find('OjsUserBundle:AnonymUserToken',$id);
        if(!$login)
            throw new NotFoundHttpException;
        return $this->redirect($this->get('router')->generate('user_list_anonym_login'));
    }
}
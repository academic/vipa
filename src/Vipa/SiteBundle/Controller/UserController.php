<?php


namespace Vipa\SiteBundle\Controller;

use Elastica\Exception\NotFoundException;
use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\CoreBundle\Params\ArticleStatuses;
use Vipa\UserBundle\Entity\CustomField;
use Vipa\UserBundle\Entity\User;
use Vipa\UserBundle\Entity\UserOauthAccount;
use Vipa\UserBundle\Form\Type\CustomFieldType;
use Vipa\UserBundle\Form\Type\UpdateUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param $slug
     * @return Response
     */
    public function profileAction($slug)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = ($slug == "me") ?
            $this->getUser() :
            $em->getRepository('VipaUserBundle:User')->findOneBy(['username' => $slug, 'enabled' => true]);
        $this->throw404IfNotFound($user);

        $data = [];
        $data['user'] = $user;
        $data['me'] = $this->getUser();

        if ($user->isPrivacy()) {
            return $this->render('VipaSiteBundle:User:private_account.html.twig', $data);
        }

        $data['journalUsers'] = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:JournalUser')
            ->findBy(['user' => $user]);

        $data['articles'] = $this
            ->getDoctrine()
            ->getRepository('VipaJournalBundle:Article')
            ->findBy([
                'submitterUser' => $user,
                'status' => ArticleStatuses::STATUS_PUBLISHED
                ],['pubdate' => 'DESC']);


        return $this->render('VipaSiteBundle:User:profile_index.html.twig', $data);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editProfileAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        /** @var User $user */
        $getUser = $this->getUser();
        if (!$getUser) {
            throw new AccessDeniedException();
        }
        $user = $em->getRepository('VipaUserBundle:User')->find($getUser->getId());
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $form = $this->createForm(new UpdateUserType(), $user)
            ->add('update', 'submit', ['label' => 'update']);
        $data = [];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, $event);
            return $this->redirectToRoute('vipa_user_edit_profile');
        }

        $data['edit_form'] = $form->createView();
        $data['entity'] = $user;

        return $this->render('VipaSiteBundle:User:update_profile.html.twig', $data);
    }

    /**
     * @return Response
     */
    public function customFieldAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }

        return $this->render('VipaSiteBundle:User:custom_field.html.twig', array('user' => $user));
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createCustomFieldAction(Request $request, $id = null)
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            /** @var CustomField $customField */
            $customField = $em->find('VipaUserBundle:CustomField', $id);
            if (!$customField) {
                throw new NotFoundException();
            }
            if ($customField->getUserId() != $user->getId()) {
                throw new AccessDeniedException();
            }
        } else {
            $customField = new CustomField();
        }

        $customFieldForm = $this->createForm(new CustomFieldType(), $customField, ['user' => $user->getId()]);

        if ($request->isMethod('POST')) {
            $customFieldForm->handleRequest($request);
            if ($customFieldForm->isValid()) {
                $customField->setUser($user);
                $em->persist($customField);
                $em->flush();

                return $this->redirect($this->get('router')->generate('vipa_user_custom_field'));
            } else {
                $session = $this->get('session');
                $bag = $session->getFlashBag();
                $bag->add('error', $this->get('translator')->trans("An error has occured!"));
                $session->save();
            }
        }
        $data = [];
        $data['form'] = $customFieldForm->createView();

        return $this->render("VipaSiteBundle:User:create_custom_field.html.twig", $data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCustomFieldAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $customField = $em->find('VipaUserBundle:CustomField', $id);
        if (!$customField) {
            throw new NotFoundException();
        }

        $em->remove($customField);
        $em->flush();

        return $this->redirect($this->get('router')->generate('vipa_user_custom_field'));
    }

    /**
     * @return Response
     */
    public function connectedAccountAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }
        $data = [];
        $data['user'] = $user;

        return $this->render('VipaSiteBundle:User:connected_account.html.twig', $data);
    }

    /**
     * @return Response
     */
    public function addConnectedAccountAction()
    {
        return $this->render('VipaSiteBundle:User:add_connected_account.html.twig');
    }

    /**
     * @param UserOauthAccount $userOauthAccount
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteConnectedAccountAction(UserOauthAccount $userOauthAccount)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($userOauthAccount);
        $em->flush();

        return $this->redirect($this->get('router')->generate('vipa_user_connected_account'));
    }

}

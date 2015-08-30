<?php


namespace Ojs\SiteBundle\Controller;

use Elastica\Exception\NotFoundException;
use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\CustomField;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserOauthAccount;
use Ojs\UserBundle\Form\Type\CustomFieldType;
use Ojs\UserBundle\Form\Type\UpdateUserType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{

    public function profileAction($slug)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = ($slug == "me") ?
            $this->getUser() :
            $em->getRepository('OjsUserBundle:User')->findOneBy(['username' => $slug]);
        $this->throw404IfNotFound($user);

        $data = [];
        $data['user'] = $user;
        $data['me'] = $this->getUser();

        if ($user->isPrivacy()) {
            return $this->render('OjsSiteBundle:User:private_account.html.twig', $data);
        }
// 'status' => 1
        $data['articles'] = $this
            ->getDoctrine()
            ->getRepository('OjsJournalBundle:Article')
            ->findBy(['submitterUser' => $user], ['pubdate' => 'DESC']);


        return $this->render('OjsSiteBundle:User:profile_index.html.twig', $data);
    }

    public function editProfileAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(new UpdateUserType(), $user)
            ->add('update', 'submit', ['label' => 'update']);
        $data = [];

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $em->persist($user);

            $em->flush();
            return $this->redirectToRoute('ojs_user_edit_profile');
        }

        $data['edit_form'] = $form->createView();
        $data['entity'] = $user;

        return $this->render('OjsSiteBundle:User:update_profile.html.twig', $data);
    }

    public function customFieldAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }

        return $this->render('OjsSiteBundle:User:custom_field.html.twig', array('user' => $user));
    }

    public function createCustomFieldAction(Request $request, $id = null)
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            /** @var CustomField $customField */
            $customField = $em->find('OjsUserBundle:CustomField', $id);
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

                return $this->redirect($this->get('router')->generate('ojs_user_custom_field'));
            } else {
                $session = $this->get('session');
                $bag = $session->getFlashBag();
                $bag->add('error', $this->get('translator')->trans("An error has occured!"));
                $session->save();
            }
        }
        $data = [];
        $data['form'] = $customFieldForm->createView();

        return $this->render("OjsSiteBundle:User:create_custom_field.html.twig", $data);
    }

    public function deleteCustomFieldAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $customField = $em->find('OjsUserBundle:CustomField', $id);
        if (!$customField) {
            throw new NotFoundException();
        }

        $em->remove($customField);
        $em->flush();

        return $this->redirect($this->get('router')->generate('ojs_user_custom_field'));
    }

    public function connectedAccountAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }
        $data = [];
        $data['user'] = $user;

        return $this->render('OjsSiteBundle:User:connected_account.html.twig', $data);
    }

    public function addConnectedAccountAction()
    {
        return $this->render('OjsSiteBundle:User:add_connected_account.html.twig');
    }

    public function addOrcidAccountAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }
        $orcid = $this->get('ojs.orcid_service');
        $code = $request->get('code');
        $orcid->setRedirectUri(
            'http://'
            . $this->container->getParameter('base_host')
            . $this->get('router')->generate('ojs_user_add_orcid_account')
        );
        if (!$code) {
            return new RedirectResponse($orcid->loginUrl());
        }
        $post = $orcid->authorize($code);
        $em = $this->getDoctrine()->getManager();
        if ($post) {
            $oauth = new UserOauthAccount();
            $oauth->setProvider('orcid')
                ->setProviderAccessToken($post->access_token)
                ->setProviderRefreshToken($post->refresh_token)
                ->setProviderUserId($post->orcid)
                ->setUser($user);
            $em->persist($oauth);
            $user->addOauthAccount($oauth);
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->get('router')->generate('ojs_user_connected_account'));
        }
        throw new \ErrorException("An error", serialize($post));
    }

    public function deleteConnectedAccountAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->find('OjsUserBundle:UserOauthAccount', $id);
        if (!$account) {
            throw new NotFoundException();
        }
        $em->remove($account);
        $em->flush();

        return $this->redirect($this->get('router')->generate('ojs_user_connected_account'));
    }
}

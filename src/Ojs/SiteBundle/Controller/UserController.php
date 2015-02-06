<?php
/**
 * Date: 12.12.14
 * Time: 10:25
 */

namespace Ojs\SiteBundle\Controller;


use Elastica\Exception\NotFoundException;
use Ojs\UserBundle\Entity\CustomField;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserOauthAccount;
use Ojs\UserBundle\Entity\UserRepository;
use Ojs\UserBundle\Event\UserEvent;
use Ojs\UserBundle\Form\CustomFieldType;
use Ojs\UserBundle\Form\UpdateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
    public function profileAction(Request $request, $slug)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['username' => $slug]);
        if ($slug == "me") {
            $user = $this->getUser();
        }
        if (!$user)
            throw new NotFoundHttpException("User not found");
        $data = [];
        $data['user'] = $user;
        $data['me'] = $this->getUser();
        return $this->render('OjsSiteBundle:User:profile_index.html.twig', $data);
    }

    public function editProfileAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user)
            throw new AccessDeniedException;
        $form = $this->createForm(new UpdateUserType(), $user);
        $data = [];

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $em->persist($user);
                $em->flush();
            } else {
                $session = $this->get('session');
                $bag = $session->getFlashBag();
                $bag->add('error', $this->get('translator')->trans("An error has occured!"));
                $session->save();
            }
            return new RedirectResponse($this->get('router')->generate('user_edit_profile'));

        } else {
        }
        $data['edit_form'] = $form->createView();
        $data['entity'] = $user;

        return $this->render('OjsSiteBundle:User:update_profile.html.twig', $data);
    }

    public function customFieldAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user)
            throw new AccessDeniedException;


        $data = [];
        $data['user'] = $user;
        return $this->render('OjsSiteBundle:User:custom_field.html.twig', $data);
    }

    public function createCustomFieldAction(Request $request, $id = null)
    {
        /** @var User $user */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($id) {
            /** @var CustomField $customField */
            $customField = $em->find('OjsUserBundle:CustomField', $id);
            if (!$customField)
                throw new NotFoundException;
            if ($customField->getUserId() != $user->getId())
                throw new AccessDeniedException;
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
                return $this->redirectToRoute('ojs_user_custom_field');
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
        if (!$customField)
            throw new NotFoundException;

        $em->remove($customField);
        $em->flush();
        return $this->redirectToRoute('ojs_user_custom_field');
    }

    public function connectedAccountAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user)
            throw new AccessDeniedException;
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
        if (!$user)
            throw new AccessDeniedException;
        $orcid = $this->get('ojs.orcid_service');
        $code = $request->get('code');
        $orcid->setRedirectUri('http://'
            . $this->container->getParameter('base_host')
            . $this->get('router')->generate('ojs_user_add_orcid_account')
        );
        if (!$code) {
            return new RedirectResponse($orcid->loginUrl());
        }
        $post = $orcid->authorize($code);
        $em = $this->getDoctrine()->getEntityManager();
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
            return $this->redirectToRoute('ojs_user_connected_account');
        }
        throw new \ErrorException;
    }

    public function deleteConnectedAccountAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $account = $em->find('OjsUserBundle:UserOauthAccount', $id);
        if (!$account)
            throw new NotFoundException;
        $em->remove($account);
        $em->flush();
        return $this->redirectToRoute('ojs_user_connected_account');
    }

    public function forgotPasswordAction(Request $request)
    {
        $data = [];
        $session = $this->get('session');
        if ($request->isMethod('POST')) {
            $username = $request->get('_username');
            $em = $this->getDoctrine()->getManager();
            /** @var User $user */
            $user = $this->get('ojs.user_provider.username_email')->loadUserByUsername($username);
            if ($user) {
                $user->setToken($user->generateToken());
                $em->persist($user);
                $em->flush();
                $mailer = $this->get('mailer');
                $message = $mailer->createMessage()
                    ->setSubject('Password Reset')
                    ->setFrom($this->container->getParameter('system_email'))
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView('OjsUserBundle:Mails/User:reset_password.html.twig', [
                        'token' => $user->getToken()]))
                    ->setContentType('text/html')
                ;
                $mailer->send($message);
                $session->getFlashBag()->add('success',
                    $this->get('translator')
                        ->trans('We will send reset token to your registered email address. Please check in a few minutes')
                );

            }
        }
        return $this->render('OjsSiteBundle:User:forgot_password.html.twig');
    }

    public function resetPasswordAction(Request $request, $token)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $userRepo */
        $userRepo = $em->getRepository('OjsUserBundle:User');
        /** @var User $user */
        $user = $userRepo->findOneBy(['token' => $token]);
        $session = $this->get('session');
        if (!$user) {
            throw new AccessDeniedException; //:(
        }
        if ($request->isMethod('POST')) {
            $newPassword = $request->get('password');
            $newPasswordConfirm = $request->get('password_confirm');
            if (empty($newPassword) || $newPassword != $newPasswordConfirm ) {

                //something is wrong!
                $session->getFlashBag()
                    ->add('error', $this->get('translator')->trans('Both of passwords not matches!'));
                $session->save();
                return $this->redirectToRoute('ojs_user_reset_password',['token'=>$token]);
            }

            // Reset and save new password
            $encoder = $this->container->get('security.encoder_factory')
                ->getEncoder($user);
            $password = $encoder->encodePassword($newPassword, $user->getSalt());
            $user->setPassword($password);
            $user->setToken('');
            $em->persist($user);
            $em->flush();

            // Dispatch mail event
            $event = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('user.password.reset', $event);
            $session->getFlashBag()->add('success', $this->get('translator')->trans('Your password has been changed.'));
            $session->save();
            return $this->redirectToRoute('login');
        }
        $data['token'] = $token;
        return $this->render('OjsSiteBundle:User:reset_password.html.twig', $data);
    }
} 
<?php

namespace Ojs\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\TokenGenerator;
use Ojs\CoreBundle\Helper\StringHelper;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserOauthAccount;
use Ojs\UserBundle\Event\UserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RegistrationController extends  BaseController
{
    public function registerAction(Request $request)
    {
        $allowanceSetting = $this
            ->getDoctrine()
            ->getRepository('OjsAdminBundle:SystemSetting')
            ->findOneBy(['name' => 'user_registration']);

        if ($allowanceSetting) {
            if (!$allowanceSetting->getValue()) {
                return $this->render(
                    'OjsSiteBundle:Site:not_available.html.twig',
                    [
                        'title' => 'title.register',
                        'message' => 'message.registration_not_available'
                    ]
                );
            }
        }


        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('ojs_user.manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);
        //Add default data for oauth login
        $session = $this->get('session');
        $oauth_login = $session->get('oauth_login', false);
        if ($oauth_login) {
            $name = explode(' ', $oauth_login['full_name']);
            $firstName = $name[0];
            unset($name[0]);
            $lastName = implode(' ', $name);
            $user
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setUsername(StringHelper::slugify($oauth_login['full_name']));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $user->generateApiKey();
            $userManager->updateUser($user);

            $tokenGenerator = new TokenGenerator();
            $user->setConfirmationToken($tokenGenerator->generateToken());

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('login');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
            if ($oauth_login) {
                $em = $this->getDoctrine()->getManager();
                $oauth = new UserOauthAccount();
                $oauth->setProvider($oauth_login['provider'])
                    ->setProviderAccessToken($oauth_login['access_token'])
                    ->setProviderRefreshToken($oauth_login['refresh_token'])
                    ->setProviderUserId($oauth_login['user_id'])
                    ->setUser($user);
                $em->persist($oauth);
                $user->addOauthAccount($oauth);
                $em->persist($user);
            }

            $session->getFlashBag()->add('success', 'registration.activation');

            $session->remove('oauth_login');
            $session->save();

            $event = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('user.register.complete', $event);

            return $response;
        }

        return $this->render(
            'OjsUserBundle:Registration:register.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('OjsUserBundle:Registration:confirmed.html.twig', array(
            'user' => $user,
        ));
    }
}

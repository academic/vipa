<?php

namespace Ojs\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGenerator;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends  BaseController
{
    public function registerAction(Request $request)
    {
        if (!$request->attributes->get('_system_setting')->isUserRegistrationActive()) {
            return $this->render(
                'OjsSiteBundle:Site:not_available.html.twig',
                [
                    'title' => 'title.register',
                    'message' => 'message.registration_not_available'
                ]
            );
        }
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('ojs_user.manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);

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

            $this->addFlash('success', 'registration.activation');

            $event = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('user.register.complete', $event);

            return $response;
        }

        return $this->render('OjsUserBundle:Registration:register.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}

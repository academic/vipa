<?php

namespace Ojs\UserBundle\Controller;

use \Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Event\RegisterEvent;
use Ojs\UserBundle\Form\CreatePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{

    /**
     * Show unconfirmed user warning page
     */
    public function unconfirmedAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('login'));
        }
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('myprofile'));
        }

        return $this->render(
            'OjsUserBundle:Security:unconfirmedUser.html.twig'
        );
    }

    public function confirmEmailAction(Request $request, $code)
    {
        /** @var Session $session */
        $session = $request->getSession();
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!$user) {
            $session->set('_security.main.target_path', $this->generateUrl('email_confirm', array('code' => $code)));
            return $this->redirect($this->generateUrl('login'), 302);
        }
        $do = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();
        /** @var FlashBag $flashBag */
        $flashBag = $session->getFlashBag();
        //check confirmation code
        if ($user->getToken() == $code) {
            // add ROLE_USER and ROLE_AUTHOR to new activated user
            $user->addRole($do->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_USER')));
            $user->addRole($do->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_AUTHOR')));
            $user->setToken(null);
            $em->persist($user);
            $em->flush();
            $flashBag->add('success', 'You\'ve confirmed your email successfully!');
            return $this->redirect($this->generateUrl('myprofile'));
        }
        $flashBag->add('error', 'There is an error while confirming your email address.' .
            '<br>Your confirmation link may be expired.');
        return $this->redirect($this->generateUrl('confirm_email_warning'));
    }

    public function loginAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl('ojs_public_index'));
        }
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'OjsUserBundle:Security:login.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
            )
        );
    }

    public function anonymLoginAction(Request $request, $hash)
    {
        if (!$hash || empty($hash) || is_null($hash)) {
            return $this->redirect($this->generateUrl('ojs_public_index'));
        }
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl('ojs_public_index'));
        }
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        //find user on document by hash
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $anonymUserRepo = $dm->getRepository('OjsUserBundle:AnonymUserToken');
        $token = $anonymUserRepo->findOneBy(['token' => $hash]);
        if (!$token || $token->getUsed()) {
            $request->getSession()->getFlashBag()->add('error', $this->get('translator')->trans("Login hash is expired/incorrect!"));
            return $this->render(
                'OjsUserBundle:Security:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                )
            );
        }
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('OjsUserBundle:User');
        $user = $userRepo->findOneBy(['id' => $token->getUserId()]);

        $newuser = true;
        //user is not new if logged already
        if ($user->getLastlogin()) {
            $newuser = false;
        }

        //Login
        $this->authenticateUser($user);

        //update used field
        $token->setUsed(true);
        $dm->persist($token);
        $dm->flush();
        if ($newuser)
            return new RedirectResponse($this->get('router')->generate('user_create_password'));
        return new RedirectResponse($this->get('router')->generate('ojs_public_index'));

    }

    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    private function authenticateUser(User $user)
    {
        $providerKey = 'main'; //  firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
    }

    public function registerAction(Request $request)
    {
        $error = null;
        $user = new User();
        $form = $this->createForm(new \Ojs\UserBundle\Form\RegisterFormType(), $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            // check user name exists
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($this->encodePassword($user, $user->getPassword()));
            $user->setToken($user->generateToken());
            $user->addRole($em->getRepository('OjsUserBundle:Role')->findOneBy(array('role' => 'ROLE_USER')));
            $user->generateApiKey();
            $user->setStatus(1);
            $user->setIsActive(0);
            $em->persist($user);
            $em->flush();
            //$this->authenticateUser($user); // auth. user

            $msgBody = $this->renderView(
                'OjsUserBundle:Mails:User/confirmEmail.html.twig', array('user' => $user)
            );

            $event = new RegisterEvent();
            $dispatcher = $this->get('event_dispatcher');
            $message = \Swift_Message::newInstance()
                ->setSubject('Ojs Account Activation')
                ->setFrom($this->container->getParameter('system_email'))
                ->setTo($user->getEmail())
                ->setBody($msgBody)
                ->setContentType('text/html');
            $this->get('mailer')->send($message);


            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Success. <br>You are registered. Check your email to activate your account.');

            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render(
            'OjsUserBundle:Security:register.html.twig', array(
                'form' => $form->createView(),
                'errors' => $form->getErrors(),
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function regenerateAPIAction(Request $request)
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            if (!$user)
                throw new AccessDeniedException("Access denied!", 403);
            $user->generateApiKey();
            $user->setIsActive(true);
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return JsonResponse::create([
                'status' => true,
                'message' => 'API key regenerated.',
                'apikey' => $user->getApiKey(),
                'callback' => 'regenerateAPI'
            ]);
        } catch (\Exception $q) {
            return JsonResponse::create([
                'status' => false,
                'message' => $q->getMessage(),
                'code' => $q->getCode()
            ]);
        }
    }

    public function createPasswordAction(Request $request)
    {
        $data = [];
        if (!$this->getUser()) {
            return new RedirectResponse($this->get('router')->generate('ojs_public_index'));
        }
        $user = $this->getUser();
        $form = $this->createForm(new CreatePasswordType(), $user);
        $form->handleRequest($request);
        if ($request->getMethod() == 'POST' && $form->isValid()) {
            $user->setPassword($this->encodePassword($user, $user->getPassword()));
            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('ojs_public_index');
        }
        $data['form'] = $form->createView();
        return $this->render('OjsUserBundle:Security:create_password.html.twig', $data);
    }

}

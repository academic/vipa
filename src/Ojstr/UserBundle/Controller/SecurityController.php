<?php

namespace Ojstr\UserBundle\Controller;

use \Ojstr\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller {

    /**
     * Show unconfirmed user warning page
     */
    public function unconfirmedAction() {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('login'));
        }
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('myprofile'));
        }

        return $this->render(
                        'OjstrUserBundle:Security:unconfirmedUser.html.twig'
        );
    }

    public function confirmEmailAction(Request $request, $code) {
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
        $flashBag = $session->getFlashBag();
        //check confirmation code
        if ($user->getToken() == $code) {
            // add ROLE_USER
            $role = $do->getRepository('OjstrUserBundle:Role')->findOneBy(array('role' => 'ROLE_USER'));
            if ($role) {
                $user->addRole($role);
                $user->setToken(null);
                $em->persist($user);
                $flashBag->add('success', 'You\'ve confirmed your email successfully!');
                return $this->redirect($this->generateUrl('myprofile'));
            }
            $flashBag->add('error', 'System error.');
            return $this->redirect($this->generateUrl('login'));
        }
        $flashBag->add('error', 'There is an error while confirming your email address.' .
                '<br>Your confirmation link may be expired.');
        return $this->redirect($this->generateUrl('confirm_email_warning'));
    }

    public function loginAction(Request $request) {
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
                        'OjstrUserBundle:Security:login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                        )
        );
    }

    private function encodePassword(User $user, $plainPassword) {
        $encoder = $this->container->get('security.encoder_factory')
                ->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    private function authenticateUser(User $user) {
        $providerKey = 'main'; //  firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
    }

    public function registerAction(Request $request) {
        $session = $request->getSession();
        $error = null;
        $user = new User();
        $form = $this->createForm(new \Ojstr\UserBundle\Form\RegisterFormType(), $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            // check user name exists
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($this->encodePassword($user, $user->getPassword()));
            $user->setToken($user->generateToken());
            $user->setStatus(1);
            $user->setIsActive(0);
            $em->persist($user);
            $em->flush();
            //$this->authenticateUser($user); // auth. user

            $msgBody = $this->renderView(
                    'OjstrUserBundle:Mails:User/confirmEmail.html.twig', array('user' => $user)
            );

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
                        'OjstrUserBundle:Security:register.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $form->getErrors(),
                        )
        );
    }

}

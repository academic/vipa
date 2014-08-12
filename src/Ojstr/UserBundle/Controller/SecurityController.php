<?php

namespace Ojstr\UserBundle\Controller;

use \Ojstr\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller {

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

    private function getRedirectUrl() {
        $key = '_security.' . $providerKey . '.target_path';
        $session = $this->getRequest()->getSession();

        // get the URL to the last page, or fallback to the homepage
        if ($session->has($key)) {
            $url = $session->get($key);
            $session->remove($key);
        } else {
            $url = $this->generateUrl('homepage');
        }
        return $url;
    }

    public function registerAction(Request $request) {
        $session = $request->getSession();
        $error = NULL;
        $user = new User();
        $form = $this->createForm(new \Ojstr\UserBundle\Form\RegisterFormType(), $user);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            // check user name exists 
            $user->setPassword($this->encodePassword($user->getPlainPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->authenticateUser($user); // auth. user
            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Success. You are registered.')
            ;
            var_dump($form->getData());
            die;
        }

        return $this->render(
                        'OjstrUserBundle:Security:register.html.twig', array(
                    'form' => $form->createView(),
                    'errors' => $form->getErrors(),
                        )
        );
    }

    public function logoutAction(Request $request) {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        return $this->redirect($this->generateUrl('login'));
    }

    public function createUserAction(Request $request) {
        $username = $request->get('_username');
        $email = $request->get('_email');
        $password = $request->get('_password');

        $factory = $this->get('security.encoder_factory');
        $user = new User();
        $encoder = $factory->getEncoder($user);
        //$user->setSalt(md5(time()));
        $pass_encoded = $encoder->encodePassword($password, $user->getSalt());
        $user->setEmail($email);
        $user->setPassword($pass_encoded);
        $user->setUsername($username);
        $user->setIsActive(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new Response('Sucess!');
    }

}

<?php

namespace Ojs\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\MultipleMail;
use Ojs\UserBundle\Form\Type\CreatePasswordType;
use PhpParser\Node\Expr\AssignOp\Mul;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class SecurityController extends BaseSecurityController
{

    /**
     * Show unconfirmed user warning page
     *
     * @return RedirectResponse|Response
     */
    public function unconfirmedAction()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('login'));
        }

        return $this->redirect($this->generateUrl('myprofile'));
    }

    /**
     * @param $code
     * @return RedirectResponse
     */
    public function confirmEmailAction($code)
    {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();

        /**
         * @var User $user
         */
        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['confirmation_token' => $code]);
        if (!$user) {
            $session->set('_security.main.target_path', $this->generateUrl('email_confirm', array('code' => $code)));

            return $this->redirect($this->generateUrl('login'), 302);
        }
        $flashBag = $session->getFlashBag();
        //check confirmation code
        if ($user->getToken() == $code) {
            $user->setToken(null);
            $user->setIsActive(true);
            $em->persist($user);
            $em->flush();
            $flashBag->add('success', 'You\'ve confirmed your email successfully!');

            return $this->redirect($this->generateUrl('myprofile'));
        }
        $flashBag->add(
            'error',
            'There is an error while confirming your email address.'.
            '<br>Your confirmation link may be expired.'
        );

        return $this->redirect($this->generateUrl('confirm_email_warning'));
    }
    /**
     * @param $code
     * @return RedirectResponse
     */
    public function multipleMailConfirmAction($code)
    {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();

        /**
         * @var MultipleMail $mail
         */
        $mail = $em->getRepository('OjsUserBundle:MultipleMail')->findOneBy(['activation_code' => $code]);
        if (!$mail) {
            $session->set('_security.main.target_path', $this->generateUrl('multiplemail_confirm', array('code' => $code)));

            return $this->redirect($this->generateUrl('login'), 302);
        }
        $flashBag = $session->getFlashBag();
        //check confirmation code
        if ($mail->getActivationCode() == $code) {
            $mail->setActivationCode(null);
            $mail->setIsConfirmed(true);
            $em->persist($mail);
            $em->flush();
            $flashBag->add('success', 'You\'ve confirmed your email successfully!');

            return $this->redirect($this->generateUrl('ojs_user_multiple_mail'));
        }
        $flashBag->add(
            'error',
            'There is an error while confirming your email address.'.
            '<br>Your confirmation link may be expired.'
        );

        return $this->redirect($this->generateUrl('confirm_email_warning'));
    }

    public function loginAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl('ojs_public_index'));
        }

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            // BC for SF < 2.6
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        }

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }

        return $this->render('OjsUserBundle:Security:login.html.twig',array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken
        ));
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->get('router')->generate('ojs_public_index'));
        }
        $data['form'] = $form->createView();

        return $this->render('OjsUserBundle:Security:create_password.html.twig', $data);
    }

    public function redirectAction()
    {
        if ($this->getUser()->isAdmin()) {
            return new RedirectResponse($this->get('router')->generate('dashboard'));
        }

        return new RedirectResponse($this->get('router')->generate('ojs_user_index'));
    }
}

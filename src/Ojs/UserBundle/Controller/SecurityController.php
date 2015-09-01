<?php

namespace Ojs\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Form\Type\CreatePasswordType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
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
            'csrf_token' => $csrfToken,
        ));
    }

    /**
     * @return JsonResponse
     */
    public function regenerateAPIAction()
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException("ojs.403");
            }
            $user->generateApiKey();
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return JsonResponse::create(
                [
                    'status' => true,
                    'message' => 'API key regenerated.',
                    'apikey' => $user->getApiKey(),
                    'callback' => 'regenerateAPI',
                ]
            );
        } catch (\Exception $q) {
            return JsonResponse::create(
                [
                    'status' => false,
                    'message' => $q->getMessage(),
                    'code' => $q->getCode(),
                ]
            );
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->get('router')->generate('ojs_public_index'));
        }
        $data['form'] = $form->createView();

        return $this->render('OjsUserBundle:Security:create_password.html.twig', $data);
    }

    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

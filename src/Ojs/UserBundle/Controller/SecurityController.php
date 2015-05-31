<?php

namespace Ojs\UserBundle\Controller;

use Ojs\UserBundle\Entity\Role;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserOauthAccount;
use Ojs\UserBundle\Event\UserEvent;
use Ojs\UserBundle\Form\CreatePasswordType;
use Ojs\UserBundle\Form\RegisterFormType;
use Ojs\Common\Controller\OjsController as Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class SecurityController extends Controller
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
        $user = $em->getRepository('OjsUserBundle:User')->findOneBy(['token' => $code]);
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
        $flashBag->add('error', 'There is an error while confirming your email address.'.
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
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'OjsUserBundle:Security:login.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(Security::LAST_USERNAME),
                'error' => $error,
            )
        );
    }

    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    public function registerAction(Request $request)
    {
        $error = null;
        $user = new User();
        $session = $this->get('session');

        //Add default data for oauth login
        $oauth_login = $session->get('oauth_login', false);
        if ($oauth_login) {
            $name = explode(' ', $oauth_login['full_name']);
            $firstName = $name[0];
            unset($name[0]);
            $lastName = implode(' ', $name);
            $user
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setUsername($this->slugify($oauth_login['full_name']))
            ;
        }
        $form = $this->createForm(new RegisterFormType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // check user name exists
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($this->encodePassword($user, $user->getPassword()));
            $user->setToken($user->generateToken());
            $user->addRole($role);
            $user->generateApiKey();
            $user->setStatus(1);
            $user->setIsActive(0);
            $em->persist($user);

            if ($oauth_login) {
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
            $em->flush();
            //$this->authenticateUser($user); // auth. user

            $session->getFlashBag()
                ->add('success', 'Success. <br>You are registered. Check your email to activate your account.');

            $session->remove('oauth_login');
            $session->save();

            $event = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('user.register.complete', $event);

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
     * @return JsonResponse
     */
    public function regenerateAPIAction()
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            if (!$user) {
                throw new AccessDeniedException("Access Denied");
            }
            $user->generateApiKey();
            $user->setIsActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return JsonResponse::create([
                'status' => true,
                'message' => 'API key regenerated.',
                'apikey' => $user->getApiKey(),
                'callback' => 'regenerateAPI',
            ]);
        } catch (\Exception $q) {
            return JsonResponse::create([
                'status' => false,
                'message' => $q->getMessage(),
                'code' => $q->getCode(),
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

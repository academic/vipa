<?php

namespace Ojs\UserBundle\Controller;

use Ojs\Common\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class OrcidController extends Controller
{
    /**
     * @param  Request          $request
     * @return RedirectResponse
     */
    public function loginAction(Request $request)
    {
        $orcid = $this->get('ojs.orcid_service');
        $code = $request->get('code');
        $post = $orcid->authorize($code);
        $em = $this->getDoctrine()->getManager();

        /** @var UserRepository $userRepo */
        $userRepo = $em->getRepository('OjsUserBundle:User');
        $user = $userRepo->getByOauthId($post->orcid, 'orcid');
        if ($user) {
            $this->authenticateUser($user);

            return $this->redirect($this->get('router')->generate('ojs_public_index'));
        }
        $session = $this->get('session');
        $session->set(
            'oauth_login',
            [
                'provider' => 'orcid',
                'access_token' => $post->access_token,
                'refresh_token' => $post->refresh_token,
                'user_id' => $post->orcid,
                'full_name' => $post->name,
            ]
        );
        $session->save();

        return $this->redirect($this->get('router')->generate('fos_user_registration_register'));
    }

    /**
     * @param User $user
     */
    private function authenticateUser(User $user)
    {
        $providerKey = 'main'; //  firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->save();
    }
}

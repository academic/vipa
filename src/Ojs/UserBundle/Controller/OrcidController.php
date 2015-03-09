<?php

namespace Ojs\UserBundle\Controller;

use Ojs\UserBundle\Entity\User;
use Ojs\UserBundle\Entity\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;

class OrcidController extends Controller
{
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
        $session->set('oauth_login', [
            'provider' => 'orcid',
            'access_token' => $post->access_token,
            'refresh_token' => $post->refresh_token,
            'user_id' => $post->orcid,
            'full_name'=>$post->name,
        ]);
        $session->save();
        return $this->redirect($this->get('router')->generate('register'));

    }


    private function authenticateUser(User $user)
    {
        $providerKey = 'main'; //  firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
        $this->get('session')->save();
    }


}

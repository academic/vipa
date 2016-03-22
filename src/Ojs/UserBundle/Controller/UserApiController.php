<?php

namespace Ojs\UserBundle\Controller;

use Ojs\CoreBundle\Controller\OjsController as Controller;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserApiController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function regenerateAPIAction()
    {
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

        $this->successFlashBag('API key regenerated.');
        return $this->redirectToRoute('ojs_user_get_apikey');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getApiKeyAction()
    {
        return $this->render('@OjsUser/API/get_api_key.html.twig');
    }
}

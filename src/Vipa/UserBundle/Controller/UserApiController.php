<?php

namespace Vipa\UserBundle\Controller;

use Vipa\CoreBundle\Controller\VipaController as Controller;
use Vipa\UserBundle\Entity\User;
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
            throw new AccessDeniedException("vipa.403");
        }
        $user->generateApiKey();
        $user->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->successFlashBag('API key regenerated.');
        return $this->redirectToRoute('vipa_user_get_apikey');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getApiKeyAction()
    {
        return $this->render('@VipaUser/API/get_api_key.html.twig');
    }
}

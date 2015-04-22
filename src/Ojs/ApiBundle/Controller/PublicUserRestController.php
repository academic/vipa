<?php

namespace Ojs\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

class PublicUserRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="check user name availability. Return true if username is available.",
     *  filters={
     *      {"name"="username", "dataType"="string"}
     *  }
     * )
     * @Get("/public/user/checkusername/{username}")
     */
    public function getUsernameCheckAction($username)
    {
        return $this->get("user.helper")->checkUsernameAvailability($username);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @ApiDoc(
     *  resource=true,
     *  description="get user by id"
     * )
     * @Get("/public/user/get/{id}")
     */
    public function getUserAction(Request $request, $id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('OjsUserBundle:User', $id);
        if ($user) {
            return JsonResponse::create(['id' => $id, 'text' => $user->getUsername() . " <" . $user->getEmail() . '>']);
        }
        throw new NotFoundHttpException;
    }

}

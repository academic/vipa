<?php

namespace Ojs\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicUserRestController extends FOSRestController
{

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="check user name availability. Return true if username is available.",
     *  parameters={
     *  {
     *          "name"="username",
     *          "dataType"="string",
     *          "required"="true",
     *          "description"="user name to check"
     *      }
     * }
     * )
     * @Get("/public/user/checkusername/")
     *
     * @param  Request $request
     * @return bool
     */
    public function getUsernameCheckAction(Request $request)
    {
        return $this->get("user.helper")->checkUsernameAvailability($request->get('username'));
    }

    /**
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @ApiDoc(
     *                                                    resource=true,
     *                                                    description="get user by id"
     *                                                    )
     * @Get("/public/user/get/{id}", defaults={"id" = null})
     */
    public function getUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('OjsUserBundle:User', $id);
        if ($user) {
            return JsonResponse::create(['id' => $id, 'text' => $user->getUsername()." <".$user->getEmail().'>']);
        }
        throw new NotFoundHttpException();
    }
}

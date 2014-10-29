<?php

namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojstr\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

class RoleRestController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Role Action",
     *  filters={
     *      {"name"="id", "dataType"="integer"}
     *  }
     * )
     * @Get("/role/{id}")
     */
    public function getRoleAction($id)
    {
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:Role')->find($id);
        if (!is_object($user)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $user;
    }

    /**
     * @todo not implemented yet
     * @ApiDoc(
     *  resource=true,
     *  description="Get Users wtih this role",
     *  parameters={
     *      {
     *          "name"="role_id",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="role id"
     *      },{
     *          "name"="page",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="offset page"
     *      },
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "required"="true",
     *          "description"="limit"
     *      }
     *  }
     * )
     * @Get("/role/{id}/users")
     */
    public function getRoleUsersAction($id)
    {
        $role_of_users = $this->getDoctrine()->getRepository('OjstrUserBundle:Role')->find($id);
        if (!is_object($role_of_users)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $role_of_user;
    }

    private function notFound()
    {
        throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
    }

}

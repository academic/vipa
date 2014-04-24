<?php

namespace Ojstr\ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Ojstr\UserBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Ojstr\UserBundle\Form\RoleRestType;

class RoleRestController extends FOSRestController {

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Role Action",
     *  filters={
     *      {"name"="id", "dataType"="integer"}
     *  }
     * )
     */
    public function getRoleAction($id) {
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:Role')->find($id);
        if (!is_object($user)) {
            $this->notFound();
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
     */
    public function getRoleUsersAction($id) {
        
    }

    private function notFound() {
        throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
    }

}

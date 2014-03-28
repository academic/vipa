<?php
namespace Ojstr\UserBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Ojstr\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class UserRestController extends FOSRestController
{
    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get User Action",
     *  filters={
     *      {"name"="username", "dataType"="string"}
     *  }
     * )
     */
    public function getUserAction($username)
    {
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findOneByUsername($username);
        if (!is_object($user)) {
            $this->notFound();
        }
        return $user;
    }


    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Users Action",
     *  requirements={
     *      {
     *          "name"="limit",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="how many objects to return"
     *      }
     *  }
     * )
     * @RestView()
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findAll();
        if (!is_array($users)) {
            throw new HttpException(404, 'User not found!');
        }
        return $users;
    }


    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete User Action",
     *  filters={
     *      {"name"="user_id", "dataType"="integer"}
     *  }
     * )
     * @RestView(statusCode=204)
     */
    public function deleteUserAction($user_id, $soft_delete = TRUE)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var User $user
         */
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findOneById($user_id);
        if (!is_object($user)) {
            $this->notFound();
        }
        if ($soft_delete) {
            $user->setStatus(-1);
        } else {
            $em->remove($user);
        }
        $em->flush();
    }

    private function notFound()
    {
        throw new HttpException(404, 'User not found');
    }
}
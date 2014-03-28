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
    public function getUsersAction(Request $request)
    {
        $limit = $request->get('limit');
        $users = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findBy(array(), array(), $limit);
        if (!is_array($users)) {
            $this->notFound();
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


    public function putUserAction($user_id)
    {
    }

    public function postUsersAction(Request $request)
    {
    }

    public function patchUsersAction(Request $request)
    {
    }


    /**
     *
     * @ApiDoc(
     *  description="Change user status",
     *  requirements={
     *      {
     *          "name"="status",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="new user status"
     *      }
     *  }
     * )
     * @RestView()
     */
    public function statusUsersAction(Request $request, $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('OjstrUserBundle:User')->findOneById($user_id);
        if (!is_object($user)) {
            $this->notFound();
        }
        $user->setStatus($request->get('status'));
        $em->flush();
    }

    private function notFound()
    {
        throw new HttpException(404, 'User not found');
    }
}
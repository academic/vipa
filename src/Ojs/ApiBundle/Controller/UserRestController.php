<?php

namespace Ojs\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\ApiBundle\Form\UserRestType;
use Ojs\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @Get("/user/{username}")
     *
     * @param $username
     * @return mixed
     */
    public function getUserAction($username)
    {
        $user = $this->getDoctrine()->getRepository('OjsUserBundle:User')->findOneBy(array('username' => $username));
        if (!is_object($user)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $user;
    }

    /**
     * @ApiDoc(
     *    resource=true,
     *    description="Get user articles",
     *    filters={
     *      {"name"="username", "dataType"="string"}
     *    }
     * )
     * @Get("/user/{username}/articles")
     *
     * @param $username
     * @return array
     */
    public function getUserArticlesAction($username)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb
            ->select("a")
            ->from("OjsJournalBundle:Article", "a")
            ->join("OjsJournalBundle:ArticleAuthor", 'aa', 'WITH', 'a=aa.article')
            ->join("OjsJournalBundle:Author", "au", "WITH", "aa.author=au")
            ->join("OjsUserBundle:User", "u", "WITH", "au.user=u")
            ->where(
                $qb->expr()->eq("u.username", ":username")
            )
            ->setParameter("username", $username);

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get User Journals",
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
     * @Get("/user/{username}/journals")
     *
     * @param $username
     */
    public function getUserJournalsAction($username)
    {
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get User Roles"
     *
     * )
     * @Get("/user/{username}/roles")
     *
     * @param $username
     */
    public function getUserRolesAction($username)
    {
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Get Users Action",
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
     *          "description"="how many objects to return"
     *      }
     *  }
     * )
     * @RestView()
     *
     * @param  Request $request
     * @return array
     */
    public function getUsersAction(Request $request)
    {
        $limit = $request->get('limit', 12);
        $page = $request->get('page', 1);

        if (empty($limit)) {
            throw new HttpException(400, 'Missing parameter : limit');
        }
        if (empty($page)) {
            throw new HttpException(400, 'Missing parameter : page');
        }
        $users = $this->getDoctrine()->getRepository('OjsUserBundle:User')->findBy(array(), array(), $limit);
        if (!is_array($users)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
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
     *
     * @param  Request $request
     * @param $user_id
     * @return View
     */
    public function deleteUserAction(Request $request, $user_id)
    {
        $destroy = $request->get('destroy');
        $em = $this->getDoctrine()->getManager();
        /**
         * @var User $user
         */
        $user = $this->getUserEntity($user_id);
        if (!is_object($user)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        if (!$destroy) {
            $user->setStatus(-1);
        } else {
            $em->remove($user);
        }
        $em->flush();

        return $this->view(null, Codes::HTTP_NO_CONTENT);
    }

    /**
     * @todo not implemented !
     * @ApiDoc(
     *  resource=true,
     *  description="Update User Action",
     *  requirements={
     *      {
     *          "name"="user_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="user id"
     *      }
     * }
     * )
     * @RestView()
     *
     * @param  Request $request
     * @param $user_id
     * @return View
     */
    public function putUserAction(Request $request, $user_id)
    {
        $entity = $this->getUserEntity($user_id);
        $form = $this->createForm(new UserRestType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->view(null, Codes::HTTP_NO_CONTENT);
        }
        throw new HttpException(400, 'Missing parameter');
    }

    /**
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Delete User Action"
     *
     * )
     * @RestView()
     *
     * @param  Request                                            $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function postUsersAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(new UserRestType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('api_get_user', array('username' => $entity->getUsername())));
        }
        throw new HttpException(400, 'Missing parameter');
    }

    public function patchUsersAction(Request $request)
    {
    }

    /**
     *
     * @ApiDoc(
     *  description="Change user status",
     *  method="PATCH",
     *  requirements={
     *      {
     *          "name"="status",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="new user status"
     *      }
     *  },
     * filters={
     *      {"name"="user_id", "dataType"="integer"}
     * }
     * )
     * @RestView()
     *
     * @param  Request $request
     * @param $user_id
     * @return User
     */
    public function statusUsersAction(Request $request, $user_id)
    {
        return $this->patch('status', $user_id, $request);
    }

    /**
     *
     * @ApiDoc(
     *  description="Change user 'isActive'",
     *  method="PATCH",
     *  requirements={
     *      {
     *          "name"="isActive",
     *          "dataType"="boolean",
     *          "requirement"="\d+",
     *          "description"="0|1"
     *      }
     *  },
     * filters={
     *      {"name"="user_id", "dataType"="integer"}
     * }
     * )
     * @RestView()
     *
     * @param  Request $request
     * @param $user_id
     * @return User
     */
    public function activeUsersAction(Request $request, $user_id)
    {
        return $this->patch('active', $user_id, $request);
    }

    protected function patch($field, $user_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('OjsUserBundle:User')->find($user_id);
        if (!is_object($user)) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }
        /* @var  $user \Ojs\UserBundle\Entity\User */
        switch ($field) {
            case 'active':
                $user->setIsActive($request->get('isActive'));
                break;
            case
            'status':
                $user->setStatus($request->get('status'));
                break;
            default:
                break;
        }
        $em->flush();

        return $user;
    }

    protected function getUserEntity($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OjsUserBundle:User')->find($id);
        if (!$entity) {
            throw new HttpException(404, 'Not found. The record is not found or route is not defined.');
        }

        return $entity;
    }
}

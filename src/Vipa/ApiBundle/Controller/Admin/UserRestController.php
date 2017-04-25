<?php

namespace Vipa\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\AdminBundle\Form\Type\UserType;
use Vipa\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class UserRestController extends FOSRestController
{
    /**
     * List all Users.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"user"},
     *   section = "user",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Users to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getUsersAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new User())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.user.handler')->all($limit, $offset);
    }

    /**
     * Get single User.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a User for a given id",
     *   output = "Vipa\UserBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the User is not found"
     *   },
     *   views = {"user"},
     *   section = "user",
     * )
     *
     * @param int     $id      the User id
     *
     * @return array
     *
     * @throws NotFoundHttpException when User not exist
     */
    public function getUserAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new User.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"user"},
     *   section = "user",
     * )
     *
     * @return FormTypeInterface
     */
    public function newUserAction()
    {
        if (!$this->isGranted('CREATE', new User())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new UserType(), null, ['csrf_protection' => false])->remove('password');
    }

    /**
     * Create a User from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new User from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"user"},
     *   section = "user",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postUserAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new User())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('vipa_api.user.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_users', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing User from the submitted data or create a new User at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the User is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"user"},
     *   section = "user",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the User id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when User not exist
     */
    public function putUserAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new User())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('vipa_api.user.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.user.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.user.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_user', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"user"},
     *   section = "user",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function patchUserAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('vipa_api.user.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );
            if (!$this->isGranted('EDIT', $entity)) {
                throw new AccessDeniedException;
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_user', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Fetch a User or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return User
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.user.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}

<?php

namespace Vipa\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Vipa\AdminBundle\Form\Type\ArticleTypesType;
use Vipa\JournalBundle\Entity\ArticleTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Vipa\ApiBundle\Exception\InvalidFormException;

class ArticleTypeRestController extends FOSRestController
{
    /**
     * List all ArticleTypes.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articletype"},
     *   section = "articletype",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing ArticleTypes.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many ArticleTypes to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getArticletypesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new ArticleTypes())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('vipa_api.article_type.handler')->all($limit, $offset);
    }

    /**
     * Get single ArticleType.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a ArticleType for a given id",
     *   output = "Vipa\ArticleTypeBundle\Entity\ArticleType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the ArticleType is not found"
     *   },
     *   views = {"articletype"},
     *   section = "articletype",
     * )
     *
     * @param int     $id      the ArticleType id
     *
     * @return array
     *
     * @throws NotFoundHttpException when ArticleType not exist
     */
    public function getArticletypeAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new ArticleType.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"articletype"},
     *   section = "articletype",
     * )
     *
     * @return FormTypeInterface
     */
    public function newArticletypeAction()
    {
        if (!$this->isGranted('CREATE', new ArticleTypes())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new ArticleTypesType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a ArticleType from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new ArticleType from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articletype"},
     *   section = "articletype",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postArticletypeAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new ArticleTypes())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('vipa_api.article_type.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_articletype', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing ArticleType from the submitted data or create a new ArticleType at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the ArticleType is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articletype"},
     *   section = "articletype",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the ArticleType id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when ArticleType not exist
     */
    public function putArticletypeAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new ArticleTypes())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('vipa_api.article_type.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('vipa_api.article_type.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('vipa_api.article_type.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_articletypes', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing article_type from the submitted data or create a new article_type at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"articletype"},
     *   section = "articletype",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the article_type id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when article_type not exist
     */
    public function patchArticletypeAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('vipa_api.article_type.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_articletypes', $routeOptions, Codes::HTTP_NO_CONTENT);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @return Response
     * @ApiDoc(
     *      resource = false,
     *      description = "Delete ArticleType",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "ArticleType ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"articletype"},
     *      section = "articletype",
     * )
     *
     */
    public function deleteArticletypeAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('vipa_api.article_type.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a ArticleType or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ArticleTypes
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('vipa_api.article_type.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}

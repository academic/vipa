<?php

namespace Ojs\ApiBundle\Controller\Admin;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Ojs\AdminBundle\Form\Type\IndexType;
use Ojs\JournalBundle\Entity\Index;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Form\FormTypeInterface;
use Ojs\ApiBundle\Exception\InvalidFormException;

class IndexRestController extends FOSRestController
{
    /**
     * List all Indexs.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"index"},
     *   section = "index",
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing Indexs.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many Indexs to return.")
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getIndexesAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        if (!$this->isGranted('VIEW', new Index())) {
            throw new AccessDeniedException;
        }
        $offset = $paramFetcher->get('offset');
        $offset = null === $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');
        return $this->container->get('ojs_api.index.handler')->all($limit, $offset);
    }

    /**
     * Get single Index.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Index for a given id",
     *   output = "Ojs\IndexBundle\Entity\Index",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the Index is not found"
     *   },
     *   views = {"index"},
     *   section = "index",
     * )
     *
     * @param int     $id      the Index id
     *
     * @return array
     *
     * @throws NotFoundHttpException when Index not exist
     */
    public function getIndexsAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('VIEW', $entity)) {
            throw new AccessDeniedException;
        }
        return $entity;
    }

    /**
     * Presents the form to use to create a new Index.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   },
     *   views = {"index"},
     *   section = "index",
     * )
     *
     * @return FormTypeInterface
     */
    public function newIndexesAction()
    {
        if (!$this->isGranted('CREATE', new Index())) {
            throw new AccessDeniedException;
        }
        return $this->createForm(new IndexType(), null, ['csrf_protection' => false]);
    }

    /**
     * Create a Index from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new Index from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"index"},
     *   section = "index",
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postIndexesAction(Request $request)
    {
        if (!$this->isGranted('CREATE', new Index())) {
            throw new AccessDeniedException;
        }
        try {
            $newEntity = $this->container->get('ojs_api.index.handler')->post(
                $request->request->all()
            );
            $routeOptions = array(
                'id' => $newEntity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_indexs', $routeOptions, Codes::HTTP_CREATED);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing Index from the submitted data or create a new Index at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when the Index is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"index"},
     *   section = "index",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the Index id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when Index not exist
     */
    public function putIndexesAction(Request $request, $id)
    {
        if (!$this->isGranted('CREATE', new Index())) {
            throw new AccessDeniedException;
        }
        try {
            if (!($entity = $this->container->get('ojs_api.index.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $entity = $this->container->get('ojs_api.index.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $entity = $this->container->get('ojs_api.index.handler')->put(
                    $entity,
                    $request->request->all()
                );
            }
            $routeOptions = array(
                'id' => $entity->getId(),
                '_format' => $request->get('_format')
            );
            return $this->routeRedirectView('api_1_get_indexs', $routeOptions, $statusCode);
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Update existing index from the submitted data or create a new index at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   },
     *   views = {"index"},
     *   section = "index",
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the index id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when index not exist
     */
    public function patchIndexesAction(Request $request, $id)
    {
        try {
            $entity = $this->container->get('ojs_api.index.handler')->patch(
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
            return $this->routeRedirectView('api_1_get_indexs', $routeOptions, Codes::HTTP_NO_CONTENT);
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
     *      description = "Delete Index",
     *      requirements = {
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "Numeric",
     *              "description" = "Index ID"
     *          }
     *      },
     *      statusCodes = {
     *          "204" = "Deleted Successfully",
     *          "404" = "Object cannot found"
     *      },
     *      views = {"index"},
     *      section = "index",
     * )
     *
     */
    public function deleteIndexesAction($id)
    {
        $entity = $this->getOr404($id);
        if (!$this->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException;
        }
        $this->container->get('ojs_api.index.handler')->delete($entity);
        return $this->view(null, Codes::HTTP_NO_CONTENT, []);
    }

    /**
     * Fetch a Index or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return Index
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($entity = $this->container->get('ojs_api.index.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }
        return $entity;
    }
}
